<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $badge = [
            [
                'name' => 'Beginner',
                'required_achievements' => 0,
            ],
            [
                'name' => 'Intermediate',
                'required_achievements' => 4,
            ],
            [
                'name' => 'Advanced',
                'required_achievements' => 8,
            ],
            [
                'name' => 'Master',
                'required_achievements' => 10,
            ],
        ];

        Badge::upsert(
            $badge,
            ['name']
        );

    }

}
