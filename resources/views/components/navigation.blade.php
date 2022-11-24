<div class="d-flex" style="margin-right: 10px">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    @foreach ($nav_items as $item)
        <li class="nav-item">
            <a class="nav-link @if ($is_item_active($item)) active @endif" aria-current="page" href="{{$item['url']}}">{{$item['title']}}</a>
        </li>
    @endforeach
</ul>

@if( auth()->user()->is_admin )
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      Admin
    </button>
    <ul class="dropdown-menu">
        @foreach ($admin_items as $item)
        <li><a class="dropdown-item @if ($is_item_active($item)) active @endif" href="{{$item['url']}}">{{$item['title']}}</a></li>
        @endforeach
    </ul>
</div>
@endif
</div>
