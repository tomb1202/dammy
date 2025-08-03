<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrawlComicCommand extends Command
{
    protected $signature = 'comic:run {maxPage?}';
    protected $description = 'Dispatch crawl jobs per category per page. If maxPage is not provided, it will use the last page of each category.';

    public function handle()
    {
        $maxPageArg = $this->argument('maxPage');
        $categories = Category::orderBy('sort')->get();

        foreach ($categories as $category) {
            $maxPage = (int) $maxPageArg;

            if (!$maxPageArg) {
                $maxRetries = 3;
                $retryCount = 0;
                $success = false;

                while ($retryCount < $maxRetries && !$success) {
                    $proxy = getRandomProxy();

                    try {
                        $httpClient = \Symfony\Component\HttpClient\HttpClient::create([
                            'proxy'       => $proxy,
                            'verify_peer' => false,
                            'verify_host' => false,
                            'timeout'     => 30,
                        ]);

                        $client = new \Goutte\Client($httpClient);
                        $crawler = $client->request('GET', $category->url);

                        $items = $crawler->filter('.tab-content-wrap ul.pager li a')->each(function ($node) {
                            return $node->text();
                        });

                        $maxPage = (!empty($items) && isset($items[count($items) - 2]))
                            ? (int) $items[count($items) - 2]
                            : 1;

                        $this->line("Category: {$category->name} → lastPage = $maxPage");
                        $success = true;
                    } catch (\Throwable $e) {
                        $retryCount++;
                        Log::warning("Retry $retryCount: Failed to get last page for {$category->name}", [
                            'proxy' => $proxy,
                            'message' => $e->getMessage(),
                        ]);

                        if ($retryCount >= $maxRetries) {
                            $this->error("Failed to get last page for {$category->name}: " . $e->getMessage());
                        } else {
                            sleep(2);
                        }
                    }
                }

                if (!$success) {
                    continue;
                }
            }

            for ($page = $maxPage; $page >= 1; $page--) {
                dispatch(new \App\Jobs\CrawlComicPagesJob($category, $page, $page))->onQueue('comics');
                $this->line("Dispatched category: {$category->name} | page $page");
            }
        }

        $this->info("All crawl jobs dispatched!");
    }
}
