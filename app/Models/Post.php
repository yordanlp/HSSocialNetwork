<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Post::class, 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'user_post', 'post_id')->wherePivot('like', '=', true);
    }

    public function dislikes()
    {
        return $this->belongsToMany(User::class, 'user_post', 'post_id')->wherePivot('like', '=', false);
    }
}
