<?php

namespace App\Console\Commands;

use App\Models\Category;
use Exception;
use Illuminate\Console\Command;
use Goutte\Client;

class CrawlCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genre:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://sayhentaii.art/genre');

        $links = $crawler->filter('.page-genres li a')->each(function ($node) {
            return $node->attr('href');
        });

        foreach ($links as $index => $link) {
            $crawler = $client->request('GET', $link);

            // Lấy tiêu đề
            $title = $crawler->filter('title')->text();
            $title = str_replace('Đọc truyện Hentai ', '', $title);
            $title = trim($title);

            // Slug
            $slug = makeSlug($title);

            // og:title
            $metaTitle = null;
            try {
                $metaTitle = $crawler->filter('meta[property="og:title"]')->attr('content');

                $metaTitle = str_replace('| Mehentai', '', $metaTitle);
                $metaTitle = trim($metaTitle);

            } catch (Exception $e) {
                $metaTitle = '';
            }

            // description
            $metaDescription = null;
            try {
                $metaDescription = $crawler->filter('meta[name="description"]')->attr('content');

                $metaDescription = str_replace('Đọc truyện tranh Hentai thể loại ', '', $metaDescription);
                $metaDescription = trim($metaDescription);

            } catch (Exception $e) {
                $metaDescription = '';
            }

            Category::updateOrCreate(
                [
                    'slug' => $slug
                ],
                [
                    'name'              => $title,
                    'slug'              => $slug,
                    'meta_title'        => $metaTitle,
                    'meta_description'  => $metaDescription,
                    'sort'              => $index +1,
                    'url'               => $link
                ]
            );
        }

        return "Crawl xong " . count($links) . " categories!";
    }
}