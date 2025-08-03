<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Truncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:run';

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
        $tables = [
            // 'categories',
            'chapter_pages',
            'chapters',
            'comic_categories',
            'comics',
            'comments',
            'follows',
            'ratings',
            'view_histories',
            'failed_jobs',
            'jobs'
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->info("Truncated table: {$table}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All comic-related tables have been truncated successfully!');
    }
}
