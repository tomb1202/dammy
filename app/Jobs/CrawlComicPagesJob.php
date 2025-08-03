<?php

namespace App\Jobs;

use App\Jobs\CrawlFullComicJob;
use App\Models\Category;
use App\Models\Comic;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CrawlComicPagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Category $category;
    public int $from;
    public int $to;

    public function __construct(Category $category, int $from = 1, int $to = 1)
    {
        $this->category = $category;
        $this->from = $from;
        $this->to = $to;
    }

    public function handle(): void
    {
        $maxRetries = 5;
        $retryCount = 0;

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

                for ($page = $this->from; $page >= $this->to; $page--) {
                    $link = $this->category->url . '?page=' . $page;

                    try {
                        $crawler = $client->request('GET', $link);

                        $titles = $crawler->filter('.tab-content-wrap .page-item-detail .post-title a')->each(fn($node) => $node->text());
                        $urls   = $crawler->filter('.tab-content-wrap .page-item-detail .post-title a')->each(fn($node) => $node->attr('href'));
                        $images = $crawler->filter('.tab-content-wrap .page-item-detail img')->each(fn($node) => $node->attr('src'));

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
                        'category' => $this->category->slug,
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
