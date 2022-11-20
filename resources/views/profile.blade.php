<x-layout>
    <div class="row">
        <div class="d-flex justify-content-center align-items-center" style="
            background-image: url('{{Request::root()."/static/images/cover_picture.jpg"}}');
            background-position: center;
            position: relative;
            background-repeat: no-repeat;
            background-size: cover;

            height: 312px; width: 100%;">
            <img style="
                width: 150px;
                aspect-ratio: 1;
                border-radius: 50%;
                " src='{{Request::root()."/static/images/profile_picture_empty.jpg"}}'/>

            @if( auth()->user()->id !== $user->id )
                <form method="post" action="{{route('user.follow', $user->id)}}">
                    @csrf
                    <button type="submit" class="btn @if($user->is_follower(auth()->user()->id)) btn-danger @else btn-success @endif"
                        style="
                        position: absolute;
                        bottom: 10px;
                        right: 10px;
                        "
                    > @if($user->is_follower(auth()->user()->id)) Unfollow @else Follow  @endif </button>
                </form>
            @endif

        </div>
    </div>
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
