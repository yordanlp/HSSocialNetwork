<x-layout :usesLivewire="true">
    <div class="d-flex flex-column gap-2 mx-auto" style="width: 40%;">
        {{ $posts->links() }}
        <livewire:posts-wrapper section="feed-posts"/>
        {{ $posts->links() }}
    </div>
</x-layout>
