<div>
    @foreach ($posts as $post)
        <livewire:post :post="$post" wire:key="{{now()}}" />
    @endforeach
</div>
