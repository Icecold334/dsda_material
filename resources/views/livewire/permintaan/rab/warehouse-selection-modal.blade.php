<x-modal name="warehouse-selection" :show="$show" maxWidth="4xl">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Pilih Gudang</h2>
            <button type="button" wire:click="closeWarehouseModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-2 max-h-[70vh] overflow-y-auto pr-2">
            @forelse ($warehousesWithStock as $warehouse)
                @php
                    $canSelect = $warehouse['can_select'] ?? false;
                @endphp

                <div
                    class="border border-gray-200 rounded p-3 {{ !$canSelect ? 'bg-gray-50 opacity-60' : 'hover:border-gray-300' }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $warehouse['name'] ?? '-' }}</h3>
                            <p class="text-xs text-gray-500">{{ $warehouse['address'] ?? '-' }}</p>
                        </div>
                        <div class="text-xs text-gray-600">
                            {{ $warehouse['available_items'] ?? 0 }}/{{ $warehouse['total_rab_items'] ?? 0 }} tersedia
                        </div>
                    </div>

                    <div class="mb-3 border-t border-gray-100 pt-3">
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach ($items as $item)
                                @php
                                    $stockItem = collect($warehouse['stocks'] ?? [])->firstWhere('item_id', $item['item_id']);
                                    $stockQty = $stockItem ? ($stockItem['qty'] ?? 0) : 0;
                                    $qtyRab = $item['qty_rab'];

                                    // Tentukan status
                                    if ($stockQty <= 0) {
                                        $status = 'Kosong';
                                        $statusClass = 'text-red-600';
                                    } elseif ($stockQty >= $qtyRab) {
                                        $status = 'Memenuhi';
                                        $statusClass = 'text-green-600';
                                    } else {
                                        $status = 'Kurang';
                                        $statusClass = 'text-yellow-600';
                                    }
                                @endphp
                                <div
                                    class="flex items-start justify-between text-xs p-2 border border-gray-100 rounded bg-white">
                                    <div class="flex-1 pr-2">
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span class="font-semibold text-gray-700">{{ $item['item_code'] }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-gray-600">{{ $item['item_category'] }}</span>
                                        </div>
                                        <div class="text-gray-500 text-[11px]">{{ $item['item_name'] }}</div>
                                    </div>
                                    <div class="text-right whitespace-nowrap ml-2">
                                        <div class="font-semibold text-gray-900 mb-0.5">
                                            {{ number_format($stockQty, 0) }}/{{ number_format($qtyRab, 0) }}
                                            {{ $item['item_unit'] }}
                                        </div>
                                        <div class="text-[11px] font-medium {{ $statusClass }}">
                                            {{ $status }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($canSelect)
                        <button type="button" wire:click="selectWarehouse('{{ $warehouse['id'] }}')"
                            class="w-full px-3 py-2 bg-gray-800 text-white text-sm rounded hover:bg-gray-900 transition">
                            Pilih Gudang Ini
                        </button>
                    @else
                        <button type="button" disabled
                            class="w-full px-3 py-2 bg-gray-200 text-gray-400 text-sm rounded cursor-not-allowed">
                            Stok Kosong - Tidak Dapat Dipilih
                        </button>
                    @endif
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    Tidak ada gudang yang tersedia
                </div>
            @endforelse
        </div>
    </div>
</x-modal>