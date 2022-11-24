<x-layout>
    <div style="display: flex; justify-content: center;">
        <div style="width: 40%;">
        {{$posts->links()}}
            @foreach ($posts as $post)
            <x-Post :post="$post" />
            <br>
            @endforeach
            {{$posts->links()}}
        </div>
    </div>
</x-layout>
