<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Request;
use Laravel\Sanctum\HasApiTokens;
use PDO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, "user_id")->orderBy('created_at', 'desc');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_user_id', 'following_user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'follower_user_id');
    }

    public function is_follower($user_id)
    {
        return $this->followers()->where('id', '=', $user_id)->count() > 0;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }


    public function likes()
    {
        return $this->belongsToMany(Post::class, 'user_post', 'user_id')->wherePivot('like', '=', true);
    }

    public function dislikes()
    {
        return $this->belongsToMany(Post::class, 'user_post', 'user_id')->wherePivot('like', '=', false);
    }

    public function likes_post($post_id)
    {
        return $this->likes()->wherePivot('post_id', '=', $post_id)->count() > 0;
    }

    public function dislikes_post($post_id)
    {
        return $this->dislikes()->wherePivot('post_id', '=', $post_id)->count() > 0;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture');
        $this->addMediaCollection('cover_picture');
    }

    public function getProfilePictureUrl()
    {
        return $this->getMedia("profile_picture")->first()?->getUrl() ?? Request::root() . "/static/images/profile_picture_empty.jpg";
    }

    public function getCoverPictureUrl()
    {
        return $this->getMedia("cover_picture")->first()?->getUrl() ?? Request::root() . "/static/images/cover_picture.jpg";
    }

    protected static function booted()
    {
        static::created(function ($user) {
            Profile::create([
                'user_id' => $user->id,
                'language' => 'en',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });
    }
}
