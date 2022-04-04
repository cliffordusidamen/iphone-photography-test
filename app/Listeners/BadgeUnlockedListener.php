<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockedListener
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
     * @param  \App\Events\BadgeUnlocked  $event
     * @return void
     */
    public function handle(BadgeUnlocked $event)
    {

        $numberOfAchievements = $event->user->achievements()->count();

        $badges = Badge::where('required_achievements', '<=', $numberOfAchievements)
            ->get();

        foreach ($badges as $badge) {
            $alreadyHasBadge = $event->user->badges()->where('badge_id', $badge->id)->exists();

            if ($alreadyHasBadge) {
                continue;
            }

            $event->user->badges()->attach($badge->id);
        }
    }
}
