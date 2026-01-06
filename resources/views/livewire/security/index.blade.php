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
            <button type="button" x-on:click="$dispatch('open-modal', 'create-security')"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                Tambah Security
            </button>
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