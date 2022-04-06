<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User achievement end-point returns the required data.
     *
     * @test
     * @return void
     */
    public function user_achievement_endpoint_returns_correct_data()
    {
        $this->seed();

        $user = User::factory()->create();

        $lesson = Lesson::first();
        event(new LessonWatched($lesson, $user));

        $comment = $user->comments()->create([
            'body' => 'This is a comment',
        ]);
        event(new \App\Events\CommentWritten($comment));

        $response = $this->get('/users/' . $user->id . '/achievements');
        $responseData = $response->json();

        $response->assertStatus(200);

        $this->assertArrayHasKey('unlocked_achievements', $responseData);
        $this->assertTrue(in_array(
            'First Comment Written',
            $responseData['unlocked_achievements']
        ));
        $this->assertTrue(in_array(
            'First Lesson Watched',
            $responseData['unlocked_achievements']
        ));


        $this->assertArrayHasKey('next_available_achievements', $responseData);
        $this->assertTrue(in_array(
            '5 Lessons Watched',
            $responseData['next_available_achievements']
        ));
        $this->assertTrue(in_array(
            '3 Comments Written',
            $responseData['next_available_achievements']
        ));


        $this->assertArrayHasKey('current_badge', $responseData);
        $this->assertEquals(
            'Beginner',
            $responseData['current_badge'],
        );


        $this->assertArrayHasKey('next_badge', $responseData);
        $this->assertEquals(
            'Intermediate',
            $responseData['next_badge'],
        );

        $this->assertArrayHasKey('remaing_to_unlock_next_badge', $responseData);
        $this->assertEquals(
            2,
            (int) $responseData['remaing_to_unlock_next_badge'],
        );
    }
}
