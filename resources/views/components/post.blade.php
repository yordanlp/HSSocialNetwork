<div class="card m-auto post_card" style="width: 100%">
    <div class="d-flex p-1 align-items-center gap-2 post_header">
        @if ($post->user->photo == null)
            <img clas="user-avatar" style="width: 40px; border-radius: 50%;" src='{{ $post->user->getProfilePictureUrl() }}' alt="user avatar" />
        @else
            <img clas="user-avatar" style="width: 40px" src="{{$post->user->photo}}" alt="user avatar" />
        @endif

        <a href="{{route("user.show", $post->user->id)}}">{{$post->user->name}}</a>

        @if( $post->getParentPostUserName() != null )
            <i class="fa fa-paper-plane" title="Replied to"></i>
            <a href="{{route("post.show", $post->parent?->id ?? -1 )}}">
                {{$getIfItsReply()}}
            </a>
        @endif

        <div class="" style="margin-left: auto">{{$post->created_at}}</div>

        @if ( auth()->check() && auth()->user()->is_admin && $isAdminRoute() )
            <form method="post" action="{{route('admin.post.destroy', $post->id)}}" enctype="multipart/form-data">
                @method("delete")
                @csrf
                <button class="btn" type="submit" title="delete">
                    <i class="fa fa-trash"></i>
                </button>
            </form>

        @elseif ( auth()->check() && auth()->user()->id == $post->user_id )
            <a class="btn ml-auto" href="{{route("post.edit", $post->id)}}" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <form method="post" action="{{route('post.destroy', $post->id)}}" enctype="multipart/form-data">
                @method("delete")
                @csrf
                <button class="btn" type="submit" title="delete">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        @endif
    </div>

    @if ($post->media->first()?->getUrl() != null)
    <div style="height: 400px; display: flex; justify-content: center;">
        <img class="card-img-top" style="max-height: 100%; height: auto; width: auto;" src="{{$post->media->first()->getUrl()}}" alt="Card image cap">
    </div>
    @endif


    <div class="card-body">
        <p class="card-text">{{$post->message}}</p>
    </div>
    <div class="d-flex gap-3 align-items-center p-2">
        @if( ! $isAdminRoute() )
            <form method="post" action="{{route("post.like", $post->id)}}">
                @csrf
                <input name="like" type="checkbox" checked hidden>
                <button type="submit" class="btn" title="Like">
                    <i class="fa fa-thumbs-up"></i>
                </button>
            </form>
        @endif
        <div style="color: blue;">{{$post->likes->count()}}</div>
        @if( ! $isAdminRoute() )
        <form method="post" action="{{route("post.like", $post->id)}}">
            @csrf
            <input name="like" type="checkbox" hidden>

                <button type="submit" class="btn" title="dislike">
                    <i class="fa fa-thumbs-down"></i>
                </button>
        </form>
        @endif
        <div style="color: red">{{$post->dislikes->count()}}</div>
        @if( ! $isAdminRoute() )
            <a href="{{route("post.show", $post->id)}}" class="btn" title="Comment" >
                <i class="fa fa-envelope"></i>
            </a>
        @endif
        <div style="color: green">{{count($post->comments)}}</div>
    </div>
</div>
