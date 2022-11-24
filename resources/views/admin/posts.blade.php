<x-layout>
    {{$posts->links()}}
    @foreach ($posts as $post)
    <x-Post :post="$post" />
    @endforeach
    {{$posts->links()}}
</x-layout>
