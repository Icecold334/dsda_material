<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold">Daftar Transfer Pengiriman</div>
            <p class="text-sm text-gray-600 mt-1">Daftar permintaan transfer dari Sudin lain kepada Anda</p>
        </div>
    </div>
    <div data-grid data-api="{{ route('transfer.pengiriman.json') }}" data-columns='{{ json_encode($data) }}'
        data-limit="10" wire:ignore>
    </div>
</div>