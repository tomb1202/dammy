<?php

namespace App\Jobs;

use App\Models\ChapterPage;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FixChapterPageImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array<int> */
    protected array $pageIds;

    public function __construct(array $pageIds)
    {
        $this->pageIds = $pageIds;
    }

    public function handle(): void
    {
        $pages = ChapterPage::whereIn('id', $this->pageIds)->get();

        foreach ($pages as $page) {
            try {
                $filename = 'chapter-' . $page->chapter_id . '-page-' . $page->page_number . '.webp';

                $downloaded = downloadImage(
                    $page->url_image,
                    $filename,
                    false,
                    'https://pubtranxzyzz.store'
                );

                if ($downloaded && $downloaded !== 'noimage.png') {
                    $page->image = $downloaded;
                    $page->save();
                    Log::info("Fixed image for ChapterPage ID: {$page->id}");
                } else {
                    Log::warning("Failed to download for ChapterPage ID: {$page->id}, URL: {$page->url_image}");
                }
            } catch (\Throwable $e) {
                Log::error("Error in FixChapterPageImageJob", [
                    'id' => $page->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
