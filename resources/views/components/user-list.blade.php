<div style="width: 80%;">
    <div class="table-responsive">
        <table class="table table-striped
        table-hover
        table-borderless
        align-middle">
            <thead>
                <caption>Users</caption>
                <tr>
                    <th>Avatar</th>
                    <th>User Name</th>
                    @if( auth()->user()->is_admin && $isAdminRoute() )
                        <th>Email</th>
                    @endif
                    <th>Number of Posts</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $user)
                        @if($user->id != auth()->user()->id )
                        <tr>
                            <td style="width: 80px"> <img class="user-avatar" src="{{$user->getProfilePictureUrl()}}" />  </td>
                            <td> <a href="{{route("user.show", $user->id)}}">{{$user->name}}</a>  </td>
                            @if( auth()->user()->is_admin && $isAdminRoute() )
                                <td> {{$user->email}} </td>
                            @endif
                            <td> {{count($user->posts)}}  </td>
                            @if( auth()->user()->is_admin && $isAdminRoute() )
                                <td>
                                    <form action="{{route('admin.user.destroy', $user->id)}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button style="background: none; border: none; text-decoration: underline; color: blue" type="submit" >Delete</button>
                                    </form>

                                </td>
                            @else
                            <td>
                                <form method="post" action="{{route('user.follow', $user->id)}}">
                                    @csrf
                                    <button type="submit" class="btn @if($user->is_follower(auth()->user()->id)) btn-danger @else btn-success @endif"
                                    > @if($user->is_follower(auth()->user()->id)) Unfollow @else Follow  @endif </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>

                </tfoot>
        </table>
    </div>

</div>
