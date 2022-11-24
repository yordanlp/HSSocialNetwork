<div class="user_card">
    <div class="user_card_body">
        <img class="user-avatar" src="{{$user->getProfilePictureUrl()}}" style="width: 40px; aspect-ratio: 1"/>
      <a href="{{route("user.show", $user->id)}}"><div class="user_name">{{$user->name}}</div></a>
    </div>
</div>
