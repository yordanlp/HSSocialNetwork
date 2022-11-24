<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

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
        return $this->belongsTo(Post::class, 'post_id')->with('user');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'user_post', 'post_id')->wherePivot('like', '=', true);
    }

    public function dislikes()
    {
        return $this->belongsToMany(User::class, 'user_post', 'post_id')->wherePivot('like', '=', false);
    }

    public function getParentPostUserName()
    {
        return $this->parent?->user?->name;
    }
}
