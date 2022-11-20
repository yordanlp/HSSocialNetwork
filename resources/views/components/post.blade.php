<div class="card m-auto" style="width: 100%">
    <div class="d-flex p-1 align-items-center gap-2">
        @if ($post->user->photo == null)
            <img clas="user-avatar" style="width: 40px; border-radius: 50%;" src="{{Request::root()."/static/images/profile_picture_empty.jpg"}}" alt="user avatar" />
        @else
            <img clas="user-avatar" style="width: 40px" src="{{$post->user->photo}}" alt="user avatar" />
        @endif

        <a href="{{route("user.show", $post->user->id)}}">{{$post->user->name}}</a>
        @if ( auth()->check() && auth()->user()->id == $post->user_id)
            <a class="btn btn-warning ml-auto" href="{{route("post.edit", $post->id)}}">Edit Post</a>
            <form method="post" action="{{route('post.destroy', $post->id)}}" enctype="multipart/form-data">
                @method("delete")
                @csrf
                <button class="btn btn-danger" type="submit">Delete Post</button>
            </form>
        @endif
        <div class="" style="margin-left: auto">{{$post->created_at}}</div>
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
        <form method="post" action="{{route("post.like", $post->id)}}">
            @csrf
            <input name="like" type="checkbox" checked hidden>
            <button type="submit" class="btn btn-primary">Like</button>
        </form>
        <div style="color: blue;">{{$post->likes->count()}}</div>
        <form method="post" action="{{route("post.like", $post->id)}}">
            @csrf
            <input name="like" type="checkbox" hidden>
            <button type="submit" class="btn btn-danger">Dislike</button>
        </form>
        <div style="color: red">{{$post->dislikes->count()}}</div>
        <a href="{{route("post.show", $post->id)}}" class="btn btn-success">Comments</a>
        <div style="color: green">{{count($post->comments)}}</div>
    </div>
</div>
