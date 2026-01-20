<div class="space-y-4">
    <div class="">
        <div class="{{ true ? 'grid' : 'hidden' }} grid-cols-2">
            <div class="">
                <div class="text-3xl font-semibold">Tambah Pengiriman</div>
            </div>
            <div class="text-right">
                <a href="{{ route('contract.index') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
            </div>
        </div>
    </div>
    <livewire:delivery.search-contract />
    <livewire:delivery.confirm-contract />
    <livewire:delivery.choose-warehouse />
    <livewire:delivery.create-table />
</div>