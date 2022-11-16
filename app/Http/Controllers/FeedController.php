<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function Index()
    {
        //TODO: Retrieve only friends posts and public posts
        $posts = Post::orderBy("created_at", "desc")->get();
        return view("feed", [
            'posts' => $posts
        ]);
    }
}
