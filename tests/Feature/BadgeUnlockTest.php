<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BadgeUnlockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that beginner badge can be unlocked for a new user.
     *
     * @test
     * @return void
     */
    public function new_user_can_unlock_beginner_badge()
    {
        $this->seed();
        $user = User::factory()->create();
        event(new BadgeUnlocked('Beginner', $user));

        $this->assertTrue($user->badges()->where('name', 'Beginner')->exists());
    }

    /**
     * Test that badge can be unlocked by writing a comment.
     *
     * @test
     * @return void
     */
    public function badge_unlocked_by_writing_comment()
    {
        $this->seed();
        $user = User::factory()
            ->has(Comment::factory()->count(8))
            ->has(Lesson::factory())
            ->create();

        $comment9 = Comment::factory()->create(['user_id' => $user->id]);
        
        event(new CommentWritten($comment9));
        
        $this->assertTrue($user->badges()->where('name', 'Beginner')->exists());
        $this->assertFalse($user->badges()->where('name', 'Intermediate')->exists());

        $comment10 = Comment::factory()->create(['user_id' => $user->id]);
        event(new CommentWritten($comment10));
        $this->assertTrue($user->badges()->where('name', 'Beginner')->exists());
        $this->assertTrue($user->badges()->where('name', 'Intermediate')->exists());
    }

    /**
     * Test that badge can be unlocked by watching a lesson.
     *
     * @test
     * @return void
     */
    public function badge_unlocked_by_watching_a_lesson()
    {
        $this->seed();
        Lesson::factory(5)->create();
        $user = User::factory()->create();

        $user->lessons()->syncWithPivotValues(range(1, 4), ['watched' => true]);
        
        $lesson5 = Lesson::find(5);
        event(new LessonWatched($lesson5, $user));
        $this->assertTrue($user->badges()->where('name', 'Beginner')->exists());

        $user->lessons()->syncWithPivotValues(range(1, 24), ['watched' => true]);
        $lesson25 = Lesson::find(25);
        event(new LessonWatched($lesson25, $user));

        $this->assertTrue($user->badges()->where('name', 'Intermediate')->exists());

    }
}
