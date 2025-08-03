<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestDownloadImage extends Command
{
    /**
     * Tên command: php artisan test:download-image
     *
     * @var string
     */
    protected $signature = 'test:download-image';

    /**
     * Mô tả command
     *
     * @var string
     */
    protected $description = 'Test download a fixed image using downloadImage helper';

    public function handle(): void
    {
        $url = 'https://sayhentaii.art/storage/images/manga/7779/4/671ddc59987de.webp';
        $filename = 'test-image.webp';
        $referer = 'https://pubtranxzyzz.store';

        $this->info("Downloading image from: {$url}");

        try {
            if (!function_exists('downloadImage')) {
                $this->error('❌ Helper downloadImage() chưa được định nghĩa!');
                return;
            }

            $downloaded = downloadImage($url, $filename, false, $referer);

            if ($downloaded && $downloaded !== 'noimage.png') {
                $this->info("✅ Download thành công, file lưu: {$downloaded}");
                Log::info("TestDownloadImage: Download thành công", ['file' => $downloaded]);
            } else {
                $this->warn("⚠️ Download thất bại hoặc trả về noimage.png");
            }
        } catch (\Throwable $e) {
            $this->error("❌ Lỗi: " . $e->getMessage());
            Log::error("TestDownloadImage: Lỗi khi download", ['error' => $e->getMessage()]);
        }
    }
}
