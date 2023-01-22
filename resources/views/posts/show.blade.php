<x-layout :usesLivewire="true">
    <div class="row">
        <div class="col-4 d-flex align-items-center p-4">
            <x-Post :post="$post"/>
        </div>
        <div class="col-4 overflow-auto" style="height: 80vh; display: flex; flex-direction: column; gap: 5px;">
            <livewire:posts-wrapper :section="'comments'"/>
        </div>
        <div class="col-4 d-flex align-items-center p-4">
            <x-create-post :parent_post="$post->id" action='{{route("post.store")}}' method='post' is_public='0' message='' />
        </div>
    </div>
</x-layout>
