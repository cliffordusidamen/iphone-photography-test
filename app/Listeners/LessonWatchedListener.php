<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LessonWatchedListener
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
     * @param  LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $event->user->lessons()->attach($event->lesson->id, [
            'watched' => true,
        ]);

        $numberOfWatchedVideos = $event->user->watched()->count();
        $achievements = Achievement::where('required_count', '<=', $numberOfWatchedVideos)
            ->lessonType()
            ->get();

        if (!$achievements->count()) {
            return;
        }

        foreach ($achievements as $achievement) {
            $alreadyAchieved = $event->user->lessonsWatchedAchievements()->where('comment_achievement_id', $achievement->id)->exists();
            if ($alreadyAchieved) {
                continue;
            }

            $event->user->lessonsWatchedAchievements()->attach($achievement->id);
        }
    }
}
