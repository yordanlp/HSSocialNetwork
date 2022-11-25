<x-layout>
    <div class="row">
        <div class="d-flex justify-content-center align-items-center" style="
            background-image: url('{{ $user->getCoverPictureUrl() }}');
            background-position: center;
            position: relative;
            background-repeat: no-repeat;
            background-size: cover;
            postition: relative;
            height: 312px; width: 100%;">

            <form style="width: 100%; height: 100%;" method="post" action="{{route('user.update')}}" enctype="multipart/form-data">
                @csrf
                @method("put")
                <label title="Select a cover image" for="cover_picture" style="width: 100%; height: 100%; cursor: pointer;">
                </label>
                <input hidden type="file" name="cover_picture" id="cover_picture" onchange="form.submit()"/>
            </form>

            <form method="post" action="{{route('user.update')}}" enctype="multipart/form-data">
                @csrf
                @method("put")
                <label title="Select a profile picture" for="profile_picture"><img style="
                    width: 150px;
                    aspect-ratio: 1;
                    border-radius: 50%;
                    cursor: pointer;
                    position:absolute;
                    left: 50%;
                    up: 50%;
                    transform: translate(-50%, -50%);
                    " src='{{ $user->getProfilePictureUrl() }}'/>
                </label>
                <input hidden type="file" name="profile_picture" id="profile_picture" onchange="form.submit()"/>
            </form>

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
            <h3>Followers</h3>
            @foreach ($user->followers as $follower)
                <x-UserCard :user="$follower"/>
            @endforeach
        </div>
        <div class="col-6" style="display: flex; flex-direction: column; gap: 10px;">
            @foreach ( $user->posts as $post)
                <x-Post :post="$post"/>
            @endforeach
        </div>
        <div class="col-3">
            <h3>Following</h3>
            @foreach ($user->following as $following)
                <x-UserCard :user="$following"/>
            @endforeach
        </div>
    </div>
</x-layout>
