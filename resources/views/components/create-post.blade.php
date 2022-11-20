
@props(['parent_post', 'action', 'method', 'message', 'is_public'])

<div class="create_post">
    <form method="{{ Str::lower($method) == 'get' ? "GET" : "POST" }}" action="{{$action}}" enctype="multipart/form-data">
        @method($method)
        @csrf
        <input type="number" id="post_id" name="post_id" value="{{$parent_post}}" hidden/>
        <div class="form-group">
            <label for="is_public">Make it public</label>
            <input type="checkbox" @if (old("is_public", $is_public) == true) checked @endif class="form-check-input" id="is_public" name="is_public">
        </div>
        <div class="form-group">
            <label for="photo">Select a Picture</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
        </div>
        <div class="form-group">
            <textarea placeholder="What are you thinking?" autofocus class="form-control" id="message" name="message">{{old('message', $message)}}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Post</button>
    </form>

    <ul class="mt-2 text-danger">
        @foreach ($errors->get('message') as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
