<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Comment;
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
                'name' => '5 Comments Written',
                'required_count' => 5,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '10 Comments Written',
                'required_count' => 10,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '25 Comment Written',
                'required_count' => 25,
                'achievement_type' => Comment::class,
            ],
            [
                'name' => '50 Comment Written',
                'required_count' => 50,
                'achievement_type' => Comment::class,
            ],
        ];

        Achievement::upsert(
            $achievements,
            ['required_count', 'achievement_type']
        );

    }
}
