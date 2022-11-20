<x-layout>
    <x-create-post :parent_post="$post->parent"
        action='{{route("post.update", $post->id)}}'
        method='put'
        message='{{$post->message}}'
        photo='{{$post->media->first()?->getUrl()}}'
        is_public='{{$post->is_public}}'
        />
</x-layout>
