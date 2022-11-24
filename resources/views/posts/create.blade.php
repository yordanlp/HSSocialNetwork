<x-layout>
    <div style="padding: 10px;">
        <x-create-post :parent_post=null action='{{route("post.store")}}' method='post' is_public='0' message='' />
    </div>
</x-layout>
