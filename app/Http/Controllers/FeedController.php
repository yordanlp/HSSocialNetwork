<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function Index()
    {
        //TODO: Retrieve only friends posts and public posts
        $user = auth()->user();
        $following_users = $user->following->map(fn ($f) => $f['id'])->toArray();

        $posts = Post::orderBy("created_at", "desc")
            ->whereNull('post_id')
            ->where(function ($query) use ($following_users) {
                $query->whereIn('id', $following_users)
                    ->orWhere('is_public', '=', true);
            })
            ->with('user')
            ->with('comments')
            ->with('likes')
            ->with('dislikes')
            ->get();

        return view("feed", [
            'posts' => $posts
        ]);
    }
}
