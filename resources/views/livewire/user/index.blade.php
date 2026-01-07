<div class="space-y-4">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Pengguna </div>
        </div>
        <div class="text-right">
            <button type="button" x-on:click="$dispatch('open-modal', 'create-user')"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                Tambah Pengguna
            </button>
        </div>
    </div>

    <div data-grid data-api="{{ route('user.json') }}" data-columns='[
        { "name": "Nama", "id": "name","width": "20%" },
        { "name": "Email", "id": "email","width": "20%" },
        { "name": "Sudin", "id": "sudin","width": "15%"  },
        { "name": "Divisi", "id": "division","width": "15%"  },
        { "name": "Jabatan", "id": "position","width": "15%"  },
        { "name": "", "id": "action" ,"width": "15%"}
    ]' data-limit="10" wire:ignore
        x-data="{ reloadGrid() { this.$el.dispatchEvent(new CustomEvent('reload-grid')); } }"
        @refresh-grid.window="reloadGrid()">
    </div>

    <livewire:user.create />

    @foreach($users as $user)
        <livewire:user.edit :user="$user" :key="'edit-' . $user->id" />
    @endforeach
</div>