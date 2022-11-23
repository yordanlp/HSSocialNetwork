<x-layout>
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
                    <th>Email</th>
                    <th>Number of Pots</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($users as $user)
                        <tr>
                            <td style="width: 80px"> <img class="user-avatar" src="{{$user->getProfilePictureUrl()}}" />  </td>
                            <td> {{$user->name}}  </td>
                            <td> {{$user->email}} </td>
                            <td> {{count($user->posts)}}  </td>
                            <td>
                                <form action="{{route('admin.user.destroy', $user->id)}}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button style="background: none; border: none; text-decoration: underline; color: blue" type="submit" >Delete</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>

                </tfoot>
        </table>
    </div>

</x-layout>
