<x-layout>
    <div class="row">
        <div class="col-3">
            Followers

        </div>
        <div class="col-6">
            @foreach ( $user->posts as $post)
                <x-Post :post="$post"/>
            @endforeach
        </div>
        <div class="col-3">
            Following
        </div>
    </div>
</x-layout>
