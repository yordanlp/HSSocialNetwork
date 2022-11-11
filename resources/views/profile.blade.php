<x-layout>
    <div class="row">
        <div class="col-3">
            Followers
            @foreach ($user->followers as $follower)
                <x-UserCard :user="$follower"/>
            @endforeach
        </div>
        <div class="col-6">
            @foreach ( $user->posts as $post)
                <x-Post :post="$post"/>
            @endforeach
        </div>
        <div class="col-3">
            Following
            @foreach ($user->following as $following)
                <x-UserCard :user="$following"/>
            @endforeach
        </div>
    </div>
</x-layout>
