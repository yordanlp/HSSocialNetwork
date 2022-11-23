<x-layout>
    @foreach ($posts as $post)
    <x-Post :post="$post" />
    @endforeach
</x-layout>
