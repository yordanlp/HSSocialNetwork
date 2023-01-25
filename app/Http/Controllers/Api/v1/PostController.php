<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private PostService $post_service;

    public function __construct(PostService $post_service)
    {
        $this->post_service = $post_service;
    }

    public function index(Request $request)
    {
        $search = $request->query('search') ?? "";
        $paginate = $request->query('paginate') ?? null;
        $user = auth()->user();
        $posts = $this->post_service->getPosts($user, $search, $paginate);

        return [
            'posts' => $posts
        ];
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

        return $post;
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
        return [
            "post" => $post
        ];
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

        return $post;
    }

    public function destroy($id)
    {
        $post = $this->post_service->destroy($id);
        return $post;
    }
}
