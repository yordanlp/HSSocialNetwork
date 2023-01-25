<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class PostService
{
    public function getPosts(User $user = null, string $search = "", ?int $paginate = null)
    {
        $following_users = ($user != null) ? $user->following->map(fn ($f) => $f['id'])->toArray() : [];

        $posts = Post::orderBy("posts.created_at", "desc")
            ->with('user', 'comments', 'likes', 'dislikes', 'media', 'parent', 'user.media')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where(function ($query) use ($search) {
                if ($search == "")
                    return $query;
                return $query->where("message", 'like', "%" . $search . "%")
                    ->orWhere("users.name", 'like', "%" . $search . "%");
            })
            ->where(function ($query) use ($following_users) {
                return $query->whereIn('user_id', $following_users)->orWhere('is_public', '=', true);
            }); //->paginate(15, ['posts.*']);

        if ($paginate != null)
            return $posts->paginate($paginate, ['posts.*']);
        return $posts->get(['posts.*']);
    }

    public function store($data, $path_to_photo = null)
    {
        $post = Post::create($data);
        if ($path_to_photo != null)
            $post->addMedia($path_to_photo)->toMediaCollection();
        return $post;
    }

    public function getPost($id, array $relations = [])
    {
        $post = Post::with(
            $relations
        )->findOrFail($id);
        return $post;
    }

    public function update($id, $data, $path_to_photo = null)
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        if ($path_to_photo != null) {
            $post->media->each(function ($item, $key) {
                $item->delete();
            });
            $post->addMedia($path_to_photo)->toMediaCollection();
        }
        return $post;
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return $post;
    }
}
