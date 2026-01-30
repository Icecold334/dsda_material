<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Stok Gudang</div>
        </div>
    </div>

    <div data-grid data-api="{{ route('stock.json') }}" data-columns='{{ json_encode($data) }}' data-limit="10"
        wire:ignore>
    </div>
</div>