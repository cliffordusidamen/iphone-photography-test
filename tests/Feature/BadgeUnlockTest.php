<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
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
    public function test_example()
    {
        $this->seed();
        $user = User::factory()->create();
        event(new BadgeUnlocked('Beginner', $user));

        $this->assertTrue($user->badges()->where('name', 'Beginner')->exists());
    }
}
