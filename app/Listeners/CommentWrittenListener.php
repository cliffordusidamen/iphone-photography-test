<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
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
     * @param  CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $user = $event->comment->user;
        $numberOfComments = $user->comments()->count();
        $achievements = Achievement::where('required_count', '<=', $numberOfComments)
            ->commentAchievements()
            ->get();

        if (!$achievements->count()) {
            return;
        }

        foreach ($achievements as $achievement) {
            $alreadyAchieved = $user->commentAchievements()->where('comment_achievement_id', $achievement->id)->exists();
            if ($alreadyAchieved) {
                continue;
            }

            event(new AchievementUnlocked($achievement->name, $user));
        }

    }
}
