<?php

namespace App\Listeners;

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
        $achievement = Achievement::where('required_count', $numberOfComments)
            ->commentAchievements()
            ->first();

        if (!$achievement) {
            return;
        }

        $alreadyAchieved = $user->commentAchievements()->where('comment_achievement_id', $achievement->id)->exists();
        if ($alreadyAchieved) {
            return;
        }
        $user->commentAchievements()->attach($achievement->id);
    }
}
