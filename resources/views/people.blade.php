<x-layout>
    <div class="user_list">
        @foreach ( $users as $user )
            <x-UserCard :user="$user"/>
        @endforeach
    </div>
</x-layout>
