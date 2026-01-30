<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Spesifikasi </div>
        </div>
        <div class="text-right">
            <x-button x-on:click="$dispatch('open-modal', 'create-item')">
                Tambah Spesifikasi
            </x-button>
        </div>
    </div>

    <div data-grid data-api="{{ route('item.json') }}" data-columns='{{ json_encode($data) }}' data-limit="10"
        wire:ignore x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:item.create />

    @foreach($items as $item)
        <livewire:item.edit :item="$item" :key="'edit-' . $item->id" />
    @endforeach
</div>