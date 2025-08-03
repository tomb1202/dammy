<?php

namespace App\Console\Commands;

use App\Models\ChapterPage;
use App\Jobs\FixChapterPageImageJob;
use Illuminate\Console\Command;

class CrawlChapterImagesCommand extends Command
{
    protected $signature = 'crawl:chapter-images {--limit=500}';
    protected $description = 'Re-download chapter images where image is missing or noimage.png';

    public function handle()
    {
        $limit = (int) $this->option('limit') ?? 500;
        $batchSize = 5;

        $this->info("Scanning chapter_pages where image is null or noimage.png...");

        $pages = ChapterPage::where(function ($q) {
            $q->whereNull('image')->orWhere('image', 'noimage.png');
        })
            ->whereNotNull('url_image')
            ->limit($limit)
            ->get();

        if ($pages->isEmpty()) {
            $this->info("No chapter pages found needing update.");
            return;
        }

        $chunks = $pages->chunk($batchSize);

        foreach ($chunks as $chunk) {
            $pageIds = $chunk->pluck('id')->toArray();
            dispatch(new FixChapterPageImageJob($pageIds))->onQueue('images');
            $this->line("Batch dispatched: " . implode(', ', $pageIds));
        }

        $this->info("Dispatch complete. Total batches: {$chunks->count()}");
    }
}
