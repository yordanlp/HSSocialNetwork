<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    private PostService $post_service;
    public function __construct(PostService $post_service)
    {
        $this->post_service = $post_service;
    }
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            "message" => ["max:250", "nullable", "required_if:photo,null"],
            "photo" => ["image", "mimes:jpeg,png,jpg,gif,svg", "max:10000", "nullable", "required_if:message,null", "file"]
        ], [
            "photo.image" => "The file uploaaded should be an image file",
            "photo.mimes" => "The file uploaaded should be an image file",
            "photo.max" => "The file should not be over 10000kb",
            "photo.file" => "The file should be an image"
        ]);

        $is_public = ($request->is_public != null);

        $data = [
            'user_id' => $user->id,
            'message' => $request->message,
            'is_public' => $is_public,
            'post_id' => $request->post_id
        ];


        $post = $this->post_service->store($data, $request->file('photo')?->getPathName());

        if ($request->post_id != null)
            return redirect()->route("post.show", $request->post_id);
        return redirect()->route("user.show", $user->id);
    }

    public function edit($id)
    {
        $post = $this->post_service->getPost($id);
        return view("posts.edit", ['post' => $post]);
    }

    public function update($id, Request $request)
    {

        $validated = $request->validate([
            "message" => ["max:250", "nullable", "required_if:photo,null"],
            "photo" => ["image", "mimes:jpeg,png,jpg,gif,svg", "max:10000", "nullable", "required_if:message,null", "file"]
        ], [
            "photo.image" => "The file uploaaded should be an image file",
            "photo.mimes" => "The file uploaaded should be an image file",
            "photo.max" => "The file should not be over 10000kb",
            "photo.file" => "The file should be an image"
        ]);

        $is_public = ($request->is_public != null);

        $data = [
            'message' => $request->message,
            'is_public' => $is_public,
        ];

        $post = $this->post_service->update($id, $data, $request->file('photo')?->getPathName());

        return redirect()->route("user.show", Auth::user()->id);
    }

    public function destroy($id)
    {
        $this->post_service->destroy($id);
        return redirect()->back();
    }

    public function show($id)
    {
        $post = $this->post_service->getPost($id, [
            'likes',
            'dislikes',
            'media',
            'user',
            'user.media',
            'comments',
            'comments.user',
            'comments.parent',
            'comments.media',
            'comments.likes',
            'comments.dislikes',
            'comments.comments'
        ]);
        Session::put('comments', $post->comments);
        return view("posts.show", [
            "post" => $post
        ]);
    }

    public function like($post_id, Request $request)
    {
        $like = $request->like === "on" ? true : false;
        $user = Auth::user();
        $post = Post::findOrFail($post_id);
        $user_likes_post = $user->likes_post($post_id);
        $user_dislikes_post = $user->dislikes_post($post_id);
        $post->likes()->detach($user->id);
        $post->dislikes()->detach($user->id);

        if ($like && !$user_likes_post) {
            $post->likes()->attach($user->id, ['like' => true]);
        }

        if (!$like && !$user_dislikes_post) {
            $post->dislikes()->attach($user->id, ['like' => false]);
        }

        return redirect()->back();
    }
}
