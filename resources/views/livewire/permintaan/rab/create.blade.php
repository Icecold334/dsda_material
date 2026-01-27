<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan dengan RAB</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
            @if($rab && $informationFilled)
                <x-secondary-button @click="$dispatch('open-modal', 'request-information-modal')" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi
                </x-secondary-button>
            @endif
            <x-secondary-button @click="$dispatch('open-modal', 'lampiran-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Lampiran
                <span
                    class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                    x-show="fileCount > 0" x-text="fileCount">
                </span>
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('permintaan.rab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <!-- Modal Pencarian RAB -->
    <x-modal name="input-rab-number" :show="$showModal" :dismissable="false">
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-2">
                <div class="text-lg text-primary-700">
                    <a href="{{ route('permintaan.rab.index') }}" wire:navigate>
                        <i class="fa-solid fa-circle-chevron-left"></i>
                    </a>
                </div>
                <div class="font-semibold text-2xl">Masukkan Nomor RAB</div>
            </div>

            <div class="flex">
                <input type="text" wire:model="rab_nomor" wire:keydown.enter="searchRab"
                    class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                    placeholder="Masukkan Nomor RAB" />

                <x-button variant="info" type="button" wire:click="searchRab">
                    Cari
                </x-button>
            </div>

            @error('rab_nomor')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>
    </x-modal>

    @if (session('rab_found'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('rab_found') }}
        </div>
    @endif

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($rab)
        <!-- Modal Informasi Permintaan -->
        <livewire:components.request-information-modal :mode="$informationFilled ? 'show' : 'create'" :isRab="true"
            :key="'request-info-modal-rab'" />

        @if(!$informationFilled)
            <div class="p-4 text-center text-amber-800 rounded-lg bg-amber-50" role="alert">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-lg font-semibold">Silakan isi informasi permintaan terlebih dahulu</p>
            </div>
        @endif

        @if($informationFilled)
            <form wire:submit="save">
                <!-- Daftar Barang dari RAB -->
                <x-card title="Daftar Barang dari RAB">
                    @if (session('error'))
                        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (!$warehouse_id)
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                            <strong>Perhatian!</strong> Silakan pilih gudang terlebih dahulu untuk melihat stok yang
                            tersedia.
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">No</th>
                                    <th scope="col" class="px-4 py-3">Kode</th>
                                    <th scope="col" class="px-4 py-3">Nama Barang</th>
                                    <th scope="col" class="px-4 py-3">Kategori</th>
                                    <th scope="col" class="px-4 py-3">Qty RAB</th>
                                    <th scope="col" class="px-4 py-3">Stok Gudang</th>
                                    <th scope="col" class="px-4 py-3">Max Qty</th>
                                    <th scope="col" class="px-4 py-3">Qty Permintaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    @php
                                        $stock = 0;
                                        if ($warehouse_id) {
                                            $stockRecord = \App\Models\Stock::where('warehouse_id', $warehouse_id)
                                                ->where('item_id', $item['item_id'])
                                                ->first();
                                            $stock = $stockRecord ? $stockRecord->qty : 0;
                                        }
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $item['item_code'] }}</td>
                                        <td class="px-4 py-3">{{ $item['item_name'] }}</td>
                                        <td class="px-4 py-3">{{ $item['item_category'] }}</td>
                                        <td class="px-4 py-3">
                                            {{ number_format($item['qty_rab'], 2) }} {{ $item['item_unit'] }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 rounded text-xs {{ $stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ number_format($stock, 2) }} {{ $item['item_unit'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($warehouse_id)
                                                {{ number_format($item['max_qty'], 2) }} {{ $item['item_unit'] }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <input type="number" wire:model.live="items.{{ $index }}.qty_request" step="0.01"
                                                    min="0" max="{{ $item['max_qty'] }}"
                                                    class="w-24 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                    {{ !$warehouse_id || $item['max_qty'] <= 0 ? 'disabled' : '' }} />
                                                <span class="text-gray-600">{{ $item['item_unit'] }}</span>
                                            </div>
                                            @error('items.' . $index . '.qty_request')
                                                <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            Tidak ada barang dalam RAB
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('permintaan.rab.index') }}" wire:navigate
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <x-button type="submit">
                        Simpan Permintaan
                    </x-button>
                </div>
            </form>
        @endif

        <!-- Modal Lampiran -->
        <livewire:components.document-upload mode="create" modelType="App\Models\RequestModel"
            category="lampiran_permintaan" label="Upload Lampiran" :multiple="true" accept="image/*,.pdf,.doc,.docx"
            modalId="lampiran-modal" :key="'doc-upload-lampiran'" />

        <!-- Modal Pilih Gudang -->
        <livewire:permintaan.rab.warehouse-selection-modal :key="'warehouse-modal-' . ($rab?->id ?? 'empty')" />
    @endif
</div>