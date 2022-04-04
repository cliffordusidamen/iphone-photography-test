<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AchievementUnlockedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AchievementUnlocked  $event
     * @return void
     */
    public function handle(AchievementUnlocked $event)
    {
        $alreadyHasAchievement = $event->user->achievements()->where('name', $event->achievement_name)->exists();

        if ($alreadyHasAchievement) {
            return;
        }

        $achievement = Achievement::where('name', $event->achievement_name)->first();

        if (!$achievement) {
            return;
        }

        $event->user->achievements()->attach($achievement->id);
    }
}
