<?php

namespace App\Console\Commands;

use App\Jobs\CrawlFullComicJob;
use App\Models\Comic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-command';

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
        $maxRetries = 5;
        $retryCount = 0;

        $link = "https://sayhentaii.art/";

        while ($retryCount < $maxRetries) {
            $proxy = getRandomProxy();

            try {
                $httpClient = \Symfony\Component\HttpClient\HttpClient::create([
                    'proxy'        => $proxy,
                    'verify_peer'  => false,
                    'verify_host'  => false,
                    'timeout'      => 25,
                    'headers'      => [
                        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122 Safari/537.36',
                        'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'vi,en-US;q=0.9,en;q=0.8',
                        'Referer'         => 'https://truyenfull.vision/',
                    ],
                ]);

                $client = new \Goutte\Client($httpClient);

                try {
                    $crawler = $client->request('GET', $link);

                    $titles = $crawler->filter('.manga-content .page-item-detail .post-title a')->each(fn($node) => $node->text());
                    $urls   = $crawler->filter('.manga-content .page-item-detail .post-title a')->each(fn($node) => $node->attr('href'));
                    $images = $crawler->filter('.manga-content .page-item-detail a img')
                        ->each(fn($node) => preg_replace('/\s+\d+w$/', '', $node->attr('data-srcset')));

                    foreach ($titles as $index => $title) {
                        $slug = makeSlug($title);

                        DB::transaction(function () use ($title, $slug, $urls, $images, $index) {
                            $comic = Comic::updateOrCreate(
                                ['slug' => $slug],
                                [
                                    'title'      => $title,
                                    'slug'       => $slug,
                                    'url'        => $urls[$index] ?? null,
                                    'url_image'  => $images[$index] ?? null,
                                    'crawl'      => 0,
                                ]
                            );

                            // Đảm bảo đã lưu vào DB
                            dispatch(new CrawlFullComicJob($comic))->onQueue('details');
                        });
                    }
                } catch (\Throwable $e) {
                    Log::error('CrawlComicPagesJob inner loop failed', [
                        'link'    => $link,
                        'proxy'   => $proxy,
                        'message' => $e->getMessage(),
                    ]);
                }
                return;
            } catch (\Throwable $e) {
                $retryCount++;

                // Tách IP để xử lý IP chết
                preg_match('/@([\d\.]+):/', $proxy, $match);
                $ip = $match[1] ?? 'unknown';

                if ($ip !== 'unknown') {
                    cache()->put("proxy_dead_$ip", true, 120);
                    rotateProxyIpByIp($ip);
                }

                if ($retryCount >= $maxRetries) {
                    Log::error('CrawlComicPagesJob failed after max retries', [
                        'proxy'    => $proxy,
                        'retry'    => $retryCount,
                        'message'  => $e->getMessage(),
                    ]);
                } else {
                    sleep(1);
                }
            }
        }
    }
}
