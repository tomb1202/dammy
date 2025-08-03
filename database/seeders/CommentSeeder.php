<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $chapterIds = Chapter::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        if (empty($chapterIds) || empty($userIds)) {
            $this->command->warn('Không có dữ liệu user hoặc chapter để tạo comments.');
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            Comment::create([
                'chapter_id' => $faker->randomElement($chapterIds),
                'user_id' => $faker->randomElement($userIds),
                'content' => $faker->sentence(mt_rand(5, 15)),
                'parent_id' => null, // hoặc $faker->optional()->numberBetween(1, 10)
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
