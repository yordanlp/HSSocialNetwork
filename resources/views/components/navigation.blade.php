<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    @foreach ($nav_items as $item)
        <li class="nav-item">
            <a class="nav-link @if ($is_item_active($item)) active @endif" aria-current="page" href="{{$item['url']}}">{{$item['title']}}</a>
        </li>
    @endforeach
    @if( auth()->user()->is_admin )
        <li class="nav-item">
            <a class="nav-link @if ($is_item_active($item)) active @endif" aria-current="page" href="{{route("admin.admin")}}">Admin</a>
        </li>
    @endif
</ul>
