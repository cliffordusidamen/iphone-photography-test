<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonsWatchedAchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that achievement is unlocked when
     * a user watches first lesson.
     *
     * @test
     * @return void
     */
    public function first_video_achievement_unlocked_after_watching_video()
    {
        $this->seed();

        $user = User::factory()->create();

        $lesson = Lesson::factory()->create();
        // Trigger the lesson watched event
        event(new LessonWatched($lesson, $user));
        // Assert that this lesson is recorded as watched for the user
        $thisLessonWatchedByUser = $user->watched()->where('id', $lesson->id)->exists();
        $this->assertTrue($thisLessonWatchedByUser);


        // assert that achievement is unlocked
        $hasFirstVideoWatchedAchievement = $user->achievements()
            ->where('name', 'First Lesson Watched')
            ->exists();

        $this->assertTrue($hasFirstVideoWatchedAchievement);
    }
}
