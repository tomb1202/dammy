<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function downloadTest(Request $request)
    {
        $url = 'https://cdn.pubtranxzyzz.store/hen/8938/1/67ed6bf5ad00c.jfif';
        $filename = 'truyen_test.png';
        $referer = 'https://pubtranxzyzz.store';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122 Safari/537.36',
                'Referer' => $referer,
            ])->get($url);

            if ($response->successful()) {
                $imageData = $response->body();

                // Lưu tạm ảnh gốc
                $tmpPath = storage_path('app/tmp_original.jfif');
                file_put_contents($tmpPath, $imageData);

                // Đọc ảnh bằng GD (giả sử JFIF là JPEG)
                $image = @imagecreatefromjpeg($tmpPath);
                if (!$image) {
                    return response()->json([
                        'message' => 'Không thể đọc ảnh bằng GD.'
                    ], 400);
                }

                // Lưu ảnh PNG vào public/images
                $savePath = storage_path('app/public/images/' . $filename);
                imagepng($image, $savePath);
                imagedestroy($image);
                unlink($tmpPath); // xóa ảnh tạm

                return response()->json([
                    'message' => 'Tải và convert ảnh thành công!',
                    'url' => asset("storage/images/{$filename}")
                ]);
            } else {
                return response()->json([
                    'message' => 'Không thể tải ảnh.',
                    'status' => $response->status()
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi xử lý ảnh.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
