<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


if (!function_exists('chapter_image_url')) {
    function chapter_image_url(?string $urlImage): string
    {
        if (empty($urlImage)) {
            return '';
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                'accept-language' => 'en-US,en;q=0.9',
                'referer' => 'https://nettruyenvio.com/',
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',
            ])->withOptions([
                'verify' => false,
            ])->get($urlImage);

            if ($response->successful() && strlen($response->body()) > 1024) {
                return $response->handlerStats()['url'] ?? '';
            }
        } catch (\Throwable $e) {
            return '';
        }

        return '';
    }
}

if (!function_exists('makeSlug')) {
    function makeSlug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}
if (!function_exists('downloadImage')) {
    function downloadImage(string $url, string $filename, bool $isCover = false, ?string $referer = null): ?string
    {
        $tmpPath = null;

        try {

            $headers = [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/122 Safari/537.36',
            ];
            if ($referer) {
                $headers['Referer'] = $referer;
            }

            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $imageData = $response->body();
            $tmpPath = storage_path('app/tmp_image_input_' . uniqid());
            file_put_contents($tmpPath, $imageData);

            $imageInfo = getimagesize($tmpPath);
            if (!$imageInfo) {
                return null;
            }

            // Xử lý các loại định dạng ảnh
            switch ($imageInfo['mime']) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($tmpPath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($tmpPath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($tmpPath);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($tmpPath);
                    break;
                default:
                    return null;
            }

            if (!$image) {
                return null;
            }

            // Chuyển sang WebP nếu chưa phải
            if (!str_ends_with(strtolower($filename), '.webp')) {
                $filename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
            }

            $folder = $isCover ? 'covers' : 'chapters';
            $storageFolder = storage_path("app/public/images/{$folder}");
            if (!file_exists($storageFolder)) {
                mkdir($storageFolder, 0777, true);
            }

            $savePath = "{$storageFolder}/{$filename}";
            imagepalettetotruecolor($image);
            imagewebp($image, $savePath, 80);
            imagedestroy($image);


            return $filename;
        } catch (\Exception $e) {
            return null;
        } finally {
            if ($tmpPath && file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }
    }
}


if (!function_exists('storeUploadedImage')) {
    /**
     * Lưu file ảnh được upload từ form và chuyển thành định dạng WebP.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param bool $isCover
     * @return string|null Đường dẫn tính từ storage/app/public/images (ví dụ: covers/abc.webp)
     */
    function storeUploadedImage($file, bool $isCover = false): ?string
    {
        try {
            $tmpPath = $file->getRealPath();
            $imageInfo = getimagesize($tmpPath);
            if (!$imageInfo) return null;

            $mime = $imageInfo['mime'];

            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($tmpPath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($tmpPath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($tmpPath);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($tmpPath);
                    break;
                default:
                    return null;
            }

            $filename = \Illuminate\Support\Str::random(6) . '.webp';
            $folder = $isCover ? 'covers' : 'chapters';
            $storageFolder = storage_path("app/public/images/{$folder}");

            if (!file_exists($storageFolder)) {
                mkdir($storageFolder, 0777, true);
            }

            $savePath = "{$storageFolder}/{$filename}";

            imagepalettetotruecolor($image);
            imagewebp($image, $savePath, 80);
            imagedestroy($image);

            return "{$folder}/{$filename}";
        } catch (\Exception $e) {
            return null;
        }
    }
}


if (!function_exists('uploadFileAdv')) {
    function uploadFileAdv($file, $name, $folder = 'uploads')
    {
        // Get the original file extension
        $extension = $file->getClientOriginalExtension();

        $filename = $name . '-' . uniqid() . '.' . $extension;

        $folderPath = storage_path('app/public/' . $folder);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Save the file
        $file->storeAs('public/' . $folder, $filename);

        return $filename;
    }
}

if (!function_exists('uploadForSetting')) {
    function uploadForSetting($file, $image, $name)
    {
        $folderDir = 'public/uploads/logo/';
        $folderPath = storage_path('app/' . $folderDir);

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $thumbName = $name . '-' . time() . '.png';

        $image = Image::make($file);
        $imageStream = $image->stream('png');

        Storage::put($folderDir . $thumbName, $imageStream->__toString());

        return $thumbName;
    }
}


if (!function_exists('sourceSetting')) {
    function sourceSetting($image)
    {
        return url('storage/uploads/logo/' . $image);
    }
}

if (!function_exists('parseReleaseTime')) {
    function parseReleaseTime($text)
    {
        $now = Carbon::now();

        if (str_contains($text, 'giây')) {
            $minutes = 0;
        } elseif (preg_match('/(\d+)\s*phút/', $text, $m)) {
            $minutes = intval($m[1]);
            return $now->subMinutes($minutes);
        } elseif (preg_match('/(\d+)\s*giờ/', $text, $m)) {
            return $now->subHours(intval($m[1]));
        } elseif (preg_match('/(\d+)\s*ngày/', $text, $m)) {
            return $now->subDays(intval($m[1]));
        } elseif (preg_match('/(\d+)\s*tháng/', $text, $m)) {
            return $now->subMonths(intval($m[1]));
        }

        return $now;
    }
}

if (!function_exists('getRandomProxy')) {
    function getRandomProxy(): string
    {
        $proxies = [
            [
                'proxy' => '103.74.107.58:8700:tombFAvCt:6Pgk1U1z',
                'change_ip_url' => 'https://api.zingproxy.com/getip/vn/a3d1f1f550d49e23afe7f67e6344c22712f991d1',
            ],
            [
                'proxy' => '103.74.107.58:8874:tombyml39:bWDAzDPz',
                'change_ip_url' => 'https://api.zingproxy.com/getip/vn/3e006eb6e1785b3626a8d6eabc42fb098878ddbd',
            ],
            [
                'proxy' => '103.183.119.19:8893:tomb03QYS:oODulQCi',
                'change_ip_url' => 'https://api.zingproxy.com/getip/vn/7a062fcf6f2dcf9fdbcfc07820863407c0bc42dd',
            ],
            [
                'proxy' => '103.183.119.19:8938:tombGQs6h:pL5DZOje',
                'change_ip_url' => 'https://api.zingproxy.com/getip/vn/5302e7cc669dd165bb5ba6bc09e9bc659f6fd188',
            ],
        ];

        while (true) {
            shuffle($proxies);

            foreach ($proxies as $item) {
                $raw = $item['proxy'];
                $changeIpUrl = $item['change_ip_url'];

                [$ip, $port, $user, $pass] = explode(':', $raw);
                $formatted = "http://{$user}:{$pass}@{$ip}:{$port}";

                try {
                    $client = Symfony\Component\HttpClient\HttpClient::create([
                        'proxy' => $formatted,
                        'timeout' => 5,
                        'verify_peer' => false,
                        'verify_host' => false,
                    ]);

                    $res = $client->request('GET', 'https://httpbin.org/ip');

                    if ($res->getStatusCode() === 200) {
                        return $formatted;
                    }
                } catch (\Throwable $e) {
                    if (!empty($changeIpUrl)) {
                        try {
                            \Illuminate\Support\Facades\Http::timeout(10)->get($changeIpUrl);
                            sleep(3);

                            // Thử lại proxy sau đổi IP
                            try {
                                $clientRetry = Symfony\Component\HttpClient\HttpClient::create([
                                    'proxy' => $formatted,
                                    'timeout' => 5,
                                    'verify_peer' => false,
                                    'verify_host' => false,
                                ]);
                                $resRetry = $clientRetry->request('GET', 'https://httpbin.org/ip');
                                if ($resRetry->getStatusCode() === 200) {
                                    return $formatted;
                                }
                            } catch (\Throwable $retryEx) {
                                // Bỏ qua
                            }
                        } catch (\Throwable $ex) {
                            // Bỏ qua
                        }
                    }
                }
            }

            sleep(2);
        }
    }
}

if (!function_exists('rotateProxyIpByIp')) {
    function rotateProxyIpByIp(string $ip): void
    {
        $map = [
            '103.74.107.58' => [
                'https://api.zingproxy.com/getip/vn/a3d1f1f550d49e23afe7f67e6344c22712f991d1',
                'https://api.zingproxy.com/getip/vn/3e006eb6e1785b3626a8d6eabc42fb098878ddbd',
            ],
            '103.183.119.19' => [
                'https://api.zingproxy.com/getip/vn/7a062fcf6f2dcf9fdbcfc07820863407c0bc42dd',
                'https://api.zingproxy.com/getip/vn/5302e7cc669dd165bb5ba6bc09e9bc659f6fd188',
            ],
        ];

        if (!isset($map[$ip])) {
            Log::info("Không tìm thấy link đổi IP cho proxy $ip");
            return;
        }

        foreach ($map[$ip] as $changeIpUrl) {
            try {
                Http::timeout(10)->get($changeIpUrl);
                Log::info("Đã gọi đổi IP thành công cho proxy $ip - $changeIpUrl");
                sleep(1); // đợi IP đổi
            } catch (\Throwable $e) {
                Log::warning("Gọi API đổi IP thất bại cho $ip", [
                    'url' => $changeIpUrl,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

