<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'required_count',
        'achievement_type',
    ];

    /**
     * Users that have earned this achievement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Filter achievements to only comment achievements.
     */
    public function scopeCommentAchievements($query)
    {
        return $query->where('achievement_type', Comment::class);
    }

    /**
     * Filter achievements to only lesson achievements.
     */
    public function scopeLessonType($query)
    {
        return $query->where('achievement_type', Lesson::class);
    }
}
