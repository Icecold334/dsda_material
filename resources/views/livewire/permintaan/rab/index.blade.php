<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Daftar Permintaan Menggunakan RAB </div>
        </div>
        <div class="text-right">
            <a href="{{ route('permintaan.rab.create') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500">
                Buat Permintaan
            </a>
        </div>
    </div>
    <div data-grid data-api="{{ route('permintaan.rab.json') }}" data-columns='{{ json_encode($data) }}' data-limit="10"
        wire:ignore>
    </div>
</div>