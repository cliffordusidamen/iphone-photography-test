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
        $alreadyHasBadge = $event->user->badges()->where('name', $event->badge_name)->exists();

        if ($alreadyHasBadge) {
            return;
        }

        $badge = Badge::where('name', $event->badge_name)->first();

        if (!$badge) {
            return;
        }

        $event->user->badges()->attach($badge->id);
    }
}
