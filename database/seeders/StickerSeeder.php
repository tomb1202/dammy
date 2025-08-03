<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StickerSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'ch' => [
                'ch01.gif', 'ch02.gif', 'ch03.gif', 'ch04.fb4fce36.gif', 'ch05.04520504.gif',
                'ch06.e577a303.gif', 'ch07.cb135bae.gif', 'ch08.gif', 'ch09.f7ef4649.gif', 'ch10.8b2cbd6d.gif',
                'ch11.da380f09.gif', 'ch12.3e94f577.gif', 'ch13.0312a4fd.gif', 'ch14.gif', 'ch15.d7a65853.gif',
                'ch16.b105b867.gif', 'ch17.gif', 'ch18.a39c57e7.gif', 'ch19.6d1fb4f6.gif', 'ch20.43a3489d.gif',
                'ch21.3f727291.gif', 'ch22.c86d989d.gif', 'ch23.2054d70f.gif', 'ch24.860c1f83.gif',
            ],
            'capoo' => [
                'capoo01.c2fa4e1f.gif', 'capoo02.fae45118.gif', 'capoo03.87550b37.gif', 'capoo04.4aba6dee.gif',
                'capoo05.95a6a393.gif', 'capoo06.c0e3b2fd.gif', 'capoo07.cf97f7f7.gif', 'capoo08.36af97ce.gif',
                'capoo09.8997fd3c.gif', 'capoo10.9b4a43be.gif', 'capoo11.a91eccbc.gif', 'capoo12.ecdedb8b.gif',
            ],
            'qoobeeem' => [
                'qoobeeem001.87c20dfb.gif', 'qoobeeem002.9254a441.gif', 'qoobeeem003.5814f5e5.gif',
                'qoobeeem004.ebc173ee.gif', 'qoobeeem005.b1236513.gif', 'qoobeeem006.a8b83c3f.gif',
                'qoobeeem007.60d394d5.gif', 'qoobeeem008.56b10a09.gif', 'qoobeeem009.dd7b7d7c.gif',
                'qoobeeem010.f7ab7341.gif', 'qoobeeem011.77e2bd31.gif', 'qoobeeem012.19744f20.gif',
                'qoobeeem013.d150c575.gif', 'qoobeeem014.5063601d.gif', 'qoobeeem015.affba474.gif',
                'qoobeeem016.5a9a4557.gif', 'qoobeeem017.7889313c.gif', 'qoobeeem018.3d272759.gif',
                'qoobeeem019.9fd789b8.gif', 'qoobeeem020.23017474.gif', 'qoobeeem021.227e2152.gif',
                'qoobeeem022.3c47c091.gif', 'qoobeeem023.0cbd6a26.gif', 'qoobeeem024.3c876d7a.gif',
                'qoobeeem025.c13e8493.gif', 'qoobeeem026.ec2086a6.gif', 'qoobeeem027.ec4a492d.gif',
                'qoobeeem028.5c536475.gif', 'qoobeeem029.016bdc38.gif', 'qoobeeem30.gif'
            ],
            'qoobeeem2' => [
                '1.jpg', '2.jpg', '3.png', '4.png', '5.jpg', '6.gif', '7.gif', '8.gif', '9.gif',
                '10.gif', '11.gif', '12.gif', '13.png', '14.png', '15.gif', '16.gif', '17.gif', '18.gif',
                '19.png', '20.jpg', '1.jpg'
            ],
            'pepe2' => [
                '1.gif', '2.gif', '3.png', '4.gif', '5.gif', '6.gif', '7.gif', '8.gif', '9.gif',
                '10.gif', '11.gif', '12.gif', '13.gif', '14.gif', '15.gif', '16.gif', '17.gif', '18.png',
                '19.png', '20.png', '1.gif'
            ]
        ];

        foreach ($types as $key => $images) {
            $typeId = DB::table('sticker_types')->insertGetId([
                'name' => Str::title($key),
                'slug' => $key,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($images as $image) {

                $img = '/img/' . $image;

                if($key == 'pepe2'){
                    $img =  '/img/pepe2/' . $image;
                }

                DB::table('stickers')->insert([
                    'type_id'       => $typeId,
                    'image'         => $img,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
