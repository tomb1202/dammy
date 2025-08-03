<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();
        $settings = [
            'logo'              => '',
            'favicon'           => '',
            'title'             => 'Truyện Hentai, Truyện Manhwa 18+ | Mehentai',
            'site_name'         => 'domain',
            'version'           => '1.0',
            'theme_color'       => '#b5114c',
            'google_analytics'  => '',
            'mail'              => '',
            'description'       => 'Đọc truyện tranh Hentai, hentai Manhwa, truyện hentaiz, Hentai Nhật Bản được update nhanh và sớm nhất trên Mehentai.',
            'introduce'         => 'Đọc truyện tranh Hentai, hentai Manhwa, truyện hentaiz, Hentai Nhật Bản được update nhanh và sớm nhất trên Mehentai.',
            'copyright'         => '© 2024 domain.com',
            'notification'      => 'Đọc truyện tranh Hentai, hentai Manhwa, truyện hentaiz, Hentai Nhật Bản được update nhanh và sớm nhất trên Mehentai.',
            'introduct_footer'  => 'Đọc truyện tranh Hentai, hentai Manhwa, truyện hentaiz, Hentai Nhật Bản được update nhanh và sớm nhất trên Mehentai.',
        ];

        foreach($settings as $key =>  $setting){
            $item = new Setting();
            $item->type = $key;
            $item->value = $setting;

            $item->save();
        }
    }
}
