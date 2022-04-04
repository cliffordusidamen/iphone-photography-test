<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $achievements = [
            [
                'name' => 'First Comment Written',
                'required_count' => 1,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '3 Comments Written',
                'required_count' => 3,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '5 Comments Written',
                'required_count' => 5,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '10 Comment Written',
                'required_count' => 10,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '20 Comment Written',
                'required_count' => 20,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => 'First Lesson Watched',
                'required_count' => 1,
                'achievement_type' => Lesson::class,
            ],
            [
                'name' => '5 Lessons Watched',
                'required_count' => 5,
                'achievement_type' => Lesson::class,
            ],
            [
                'name' => '10 Lessons Watched',
                'required_count' => 10,
                'achievement_type' => Lesson::class,
            ],
            [
                'name' => '25 Lesson Watched',
                'required_count' => 25,
                'achievement_type' => Lesson::class,
            ],
            [
                'name' => '50 Lesson Watched',
                'required_count' => 50,
                'achievement_type' => Lesson::class,
            ],
        ];

        Achievement::upsert(
            $achievements,
            ['required_count', 'achievement_type']
        );

    }
}
