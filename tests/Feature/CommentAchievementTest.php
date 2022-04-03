<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentAchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that first comment achievement is earned when
     * a user makes their first comment.
     *
     * @test
     * @return void
     */
    public function first_comment_achievement_earned_on_comment_creation()
    {
        $this->seed();

        $user = User::factory()->create();

        $comment = $user->comments()->create([
            'body' => 'This is a comment',
        ]);

        event(new \App\Events\CommentWritten($comment));

        $hasFirstCommentAchievement = $user->achievements()
            ->where('name', 'First Comment Written')
            ->exists();

        $this->assertTrue($hasFirstCommentAchievement);
    }
}
