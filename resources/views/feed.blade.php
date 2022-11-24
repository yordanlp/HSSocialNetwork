<x-layout>
    <div class="d-flex flex-column gap-2 mx-auto" style="width: 40%;">
        {{ $posts->links() }}
        @foreach ($posts as $post)
        <x-Post :post="$post" />
        @endforeach
        {{ $posts->links() }}
    </div>
</x-layout>
