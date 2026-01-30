<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Sudin </div>
        </div>
        <div class="text-right">
            <x-button variant="primary" type="button" x-on:click="$dispatch('open-modal', 'create-sudin')">
                Tambah Sudin
            </x-button>
        </div>
    </div>

    <div data-grid data-api="{{ route('sudin.json') }}" data-columns='{{ json_encode($data) }}' data-limit="10"
        wire:ignore x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:sudin.create />

    @foreach($sudins as $sudin)
        <livewire:sudin.edit :sudin="$sudin" :key="'edit-' . $sudin->id" />
    @endforeach
</div>