<x-layout>
    <div style="display: flex; align-items: center; flex-direction: column; gap: 5px;">
        <x-user-list :users="$users"/>
        {{$users->links()}}
    </div>
</x-layout>
