<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Comic;
use App\Models\ComicCategory;
use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class CrawlFullComicJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Comic $comic;

    public function __construct(Comic $comic)
    {
        $this->comic = $comic;
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
                    'timeout'      => 30,
                    'headers'      => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122 Safari/537.36',
                        'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    ],
                ]);

                $client = new \Goutte\Client($httpClient);
                $crawler = $client->request('GET', $this->comic->url);
                $slug = $this->comic->slug;

                // Trạng thái truyện
                $statusText = $crawler->filter('.post-content .summary-content')->each(fn($n) => $n->text());
                $status = $statusText[count($statusText) - 2] ?? 'Truyện Full';
                $statusSlug = makeSlug($status);
                $this->comic->status = match ($statusSlug) {
                    'truyen-full' => 'complete',
                    'dang-ra'     => 'ongoing',
                    default       => 'unknown',
                };

                // Ảnh bìa
                try {
                    $imageUrl = $crawler->filter('.summary_image img')->attr('src');
                    $this->comic->image = downloadImage($imageUrl, $slug . '.webp', true) ?? 'noimage.png';
                } catch (\Throwable) {
                    $this->comic->image = 'noimage.png';
                }

                // SEO
                try {
                    $this->comic->meta_title = trim(str_replace(' - Mehentai', '', $crawler->filter('meta[property="og:title"]')->attr('content')));
                } catch (\Throwable) {
                }
                try {
                    $this->comic->meta_description = trim(str_replace(' tại Mehentai', '', $crawler->filter('meta[name="description"]')->attr('content')));
                } catch (\Throwable) {
                }

                // View & status
                $this->comic->views   = rand(1500, 30000);
                $this->comic->votes   = rand(0, 200);
                $this->comic->ratings = 0;
                $this->comic->crawl   = 1;
                $this->comic->save();

                // Thể loại
                $genders = $crawler->filter('.post-content .genres-content a')->each(fn($n) => $n->text());
                $existingCategories = Category::whereIn('name', $genders)->get()->keyBy('name');

                foreach ($genders as $name) {
                    $category = $existingCategories[$name] ?? Category::create([
                        'name' => $name,
                        'slug' => makeSlug($name)
                    ]);

                    ComicCategory::updateOrCreate(
                        ['comic_id' => $this->comic->id, 'category_id' => $category->id]
                    );
                }

                // Crawl chapters
                $chapters = $crawler->filter('.wp-manga-chapter')->each(function ($node) {
                    $titleNode = $node->filter('a');
                    $viewsNode = $node->filter('.number-view');
                    $dateNode  = $node->filter('.chapter-release-date i');

                    return [
                        'title'   => trim($titleNode->text()),
                        'url'     => $titleNode->attr('href'),
                        'views'   => isset($viewsNode) ? filter_var($viewsNode->text(), FILTER_SANITIZE_NUMBER_INT) : 0,
                        'release' => isset($dateNode) ? trim($dateNode->text()) : null,
                    ];
                });

                foreach ($chapters as $item) {
                    $title = $item['title'];
                    $url = $item['url'];
                    $views = $item['views'] ?? rand(2000, 24000);
                    $releaseTime = $item['release'] ? parseReleaseTime($item['release']) : now();

                    $chapterSlug = makeSlug($title);
                    preg_match('/(\d+(\.\d+)?)/', $title, $match);
                    $number = isset($match[1]) ? floatval($match[1]) : 0;

                    $chapter = Chapter::firstOrCreate(
                        ['comic_id' => $this->comic->id, 'slug' => $chapterSlug],
                        [
                            'title'            => $title,
                            'number'           => $number,
                            'url'              => $url,
                            'views'            => $views,
                            'crawl_created_at' => $releaseTime,
                            'crawl_updated_at' => $releaseTime,
                        ]
                    );

                    // Skip nếu chương đã xử lý rồi
                    if ($chapter->crawl == 1) continue;

                    try {
                        $chapterCrawler = $client->request('GET', $url);

                        $metaTitle = $chapterCrawler->filter('meta[property="og:title"]')->attr('content') ?? '';
                        $metaDesc  = $chapterCrawler->filter('meta[name="description"]')->attr('content') ?? '';

                        $chapter->update([
                            'meta_title'       => str_replace(' - Mehentai', '', $metaTitle),
                            'meta_description' => str_replace(' - Mehentai', '', $metaDesc),
                            'crawl'            => 1,
                        ]);

                        $images = $chapterCrawler->filter('#chapter_content .page-break img')->each(
                            fn($node) => $node->attr('src')
                        );

                        // Xoá ảnh cũ nếu có
                        ChapterPage::where('chapter_id', $chapter->id)->delete();

                        // Tạo batch insert
                        $data = [];
                        foreach ($images as $i => $urlImage) {
                            $data[] = [
                                'chapter_id'  => $chapter->id,
                                'page_number' => $i + 1,
                                'sort'        => $i + 1,
                                'url_image'   => $urlImage,
                                'image'       => null,
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ];
                        }

                        if (!empty($data)) {
                            ChapterPage::insert($data);
                        }
                    } catch (\Throwable $e) {
                        Log::error('Crawl chapter failed', [
                            'chapter' => $url,
                            'msg'     => $e->getMessage(),
                        ]);
                    }
                }

                // Update comic time
                $first = Chapter::where('comic_id', $this->comic->id)->orderBy('created_at')->first();
                $latest = Chapter::where('comic_id', $this->comic->id)->orderByDesc('updated_at')->first();

                if ($first) {
                    $this->comic->created_at = $first->created_at;
                }
                if ($latest) {
                    $this->comic->updated_at = $latest->updated_at;
                }

                $this->comic->save();
                return;
            } catch (\Throwable $e) {
                $retryCount++;

                // Gắn cờ IP die
                preg_match('/@([\d\.]+):/', $proxy, $match);
                $ip = $match[1] ?? 'unknown';

                if ($ip !== 'unknown') {
                    cache()->put("proxy_dead_$ip", true, 120);
                    rotateProxyIpByIp($ip);
                }

                if ($retryCount >= $maxRetries) {
                    Log::error('CrawlFullComicJob failed after max retries', [
                        'comic'   => $this->comic->slug,
                        'proxy'   => $proxy,
                        'retries' => $retryCount,
                        'message' => $e->getMessage(),
                    ]);
                } else {
                    sleep(2);
                }
            }
        }
    }
}
