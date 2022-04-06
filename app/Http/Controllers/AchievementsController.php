<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => $user->achievements->pluck('name', 'name')->keys(),
            'next_available_achievements' => $user->next_available_achievements,
            'current_badge' => $user->current_badge,
            'next_badge' => $user->next_badge ? $user->next_badge->name : '',
            'remaing_to_unlock_next_badge' => $user->achievements_for_next_badge,
        ]);
    }
}
