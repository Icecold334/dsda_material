<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Security </div>
        </div>
        <div class="text-right">
            <x-primary-button x-on:click="$dispatch('open-modal', 'create-security')">
                Tambah Security
            </x-primary-button>
        </div>
    </div>

    <div data-grid data-api="{{ route('security.json') }}" data-columns='[
        { "name": "Nama", "id": "name","width": "40%" },
        { "name": "Sudin", "id": "sudin","width": "40%"  },
        { "name": "", "id": "action" ,"width": "20%"}
    ]' data-limit="10" wire:ignore
        x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:security.create />

    @foreach($securities as $security)
        <livewire:security.edit :security="$security" :key="'edit-' . $security->id" />
    @endforeach
</div>