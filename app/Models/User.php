<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * The achievements that the user has earned.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }

    /**
     * The comment achievements that the user has earned.
     */
    public function commentAchievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->where('achievement_type', Comment::class);
    }

    /**
     * The comment achievements that the user has earned.
     */
    public function lessonsWatchedAchievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->where('achievement_type', Lesson::class);
    }

    /**
     * Badges that the user has earned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    /**
     * Get a list of next available comment and lesson achievements
     * for the user.
     *
     * @return void
     */
    public function getNextAvailableAchievementsAttribute()
    {
        $nextAvailableAchievements = [];
        $numberOfComments = $this->comments()->count();
        $nextCommentAchievement = Achievement::commentAchievements()
            ->where('required_count', '>', $numberOfComments)
            ->orderBy('required_count')
            ->first();
        if ($nextCommentAchievement) {
            $nextAvailableAchievements[] = $nextCommentAchievement->name;
        }
    
        $numberOfLessonsWatched = $this->watched()->count();
        $nextLessonAchievement = Achievement::lessonType()
            ->where('required_count', '>', $numberOfLessonsWatched)
            ->orderBy('required_count')
            ->first();
        if ($nextLessonAchievement) {
            $nextAvailableAchievements[] = $nextLessonAchievement->name;
        }

        return $nextAvailableAchievements;
    }

    /**
     * Get the current badge
     * 
     * @return string
     */
    public function getCurrentBadgeAttribute()
    {
        $lastBadge = $this->badges()->latest()->first();
        return !$lastBadge ? '' : $lastBadge->name;
    }

    /**
     * Get the next badge that this user can unlock.
     * 
     * @return \App\Models\Badge|null
     */
    public function getNextBadgeAttribute()
    {
        $numberOfBadges = $this->badges()->count();
        $nextBadge = Badge::where('required_achievements', '>', $numberOfBadges)
            ->orderBy('required_achievements')
            ->first();
        return !$nextBadge ? null : $nextBadge;
    }

    /**
     * Get number of achievements remaining to unlock next badge.
     * 
     * @return int
     */
    public function getAchievementsForNextBadgeAttribute()
    {
        $numberOfAchievements = $this->achievements()->count();
        if (!$this->next_badge) {
            return 0;
        }

        return $this->next_badge->required_achievements - $numberOfAchievements;
    }
}
