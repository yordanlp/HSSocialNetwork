<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\GoogleTranslate;
use App\Services\PostService;
use Database\Seeders\PostSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\returnSelf;

class FeedController extends Controller
{
    private PostService $post_service;

    public function __construct()
    {
        $this->post_service = new PostService();
    }
    public function Index(Request $request)
    {
        $search = $request->query('search') ?? "";
        $user = auth()->user();

        $posts = $this->post_service->getPosts($user, $search, 5);
        Session::put('feed-posts', $posts);

        return view("feed", [
            'posts' => $posts
        ]);
    }
}
