<x-layout>
    <div class="d-flex flex-column gap-2 mx-auto" style="width: 50%;">
        @foreach ($posts as $post)
        <x-Post :post="$post" />
        @endforeach
    </div>
</x-layout>
