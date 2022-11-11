
@props(['parent_post'])

<div class="create_post">
    <form method="POST" action="{{route("post.store")}}">
        @csrf
        <input type="number" id="post_id" name="post_id" value="{{$parent_post}}" hidden/>
        <div class="form-group">
            <label for="is_public">Make it public</label>
            <input type="checkbox" class="form-check-input" id="is_public" name="is_public">
        </div>
        <div class="form-group">
            <label for="photo">Select a Picture</label>
            <input type="file" class="form-control-file" id="photo" name="photo" accept=".jpg,.png,.bmp">
        </div>
        <div class="form-group">
            <textarea placeholder="What are you thinking?" autofocus class="form-control" id="message" name="message">{{old('message', '')}}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Post</button>
    </form>

    <ul class="mt-2 text-danger">
        @foreach ($errors->get('message') as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>