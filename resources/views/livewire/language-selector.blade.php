<div class="language_selector" style="display: flex; gap: 5px; margin-left: auto; align-items: center">
    @foreach ($languages as $language => $description)
        <div title="{{$description}}"
            class="@if($language == auth()->user()->profile->language) active @endif"
            wire:click='changeLanguage("{{$language}}")'
            wire:key="{{$language}}"
        >
            <img alt="{{$description}}" style="height: 20px; aspect-ratio:  4/3" src="{{url('/static/images/flags/'.$language.'.svg');}}" />
        </div>
    @endforeach
</div>
