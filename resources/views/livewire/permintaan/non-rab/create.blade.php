<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan Non RAB</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
            @if($informationFilled)
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
            <x-button variant="secondary" href="{{ route('permintaan.nonRab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    @if (session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Modal Informasi Permintaan -->
    <livewire:components.request-information-modal :mode="'create'" :isRab="false" :key="'request-info-modal'" />

    @if($informationFilled || true)
    <form wire:submit="save">
        <x-card title="Tambah Barang">
            <div class="space-y-4">
                @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ session('error') }}
                </div>
                @endif

                @if (!$warehouse_id)
                <div class="p-4 text-sm text-amber-800 rounded-lg bg-amber-50" role="alert">
                    Isi informasi permintaan terlebih dahulu untuk menambahkan barang
                </div>
                @else
                <div class="flex items-center justify-between">
                    <x-input-label for="item_type_id" value="Tipe Barang" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <livewire:components.select-input wire:model.live="item_type_id"
                            :options="$itemTypes->pluck('name', 'id')" placeholder="-- Pilih Tipe Barang --"
                            :disabled="count($items) > 0" :key="'type-select-' . $warehouse_id . '-' . count($items)" />
                        <x-input-error :messages="$errors->get('item_type_id')" class="mt-2" />
                        @if(count($items) > 0)
                        <p class="mt-1 text-xs text-gray-500">Tipe barang tidak dapat diubah setelah ada item
                            ditambahkan</p>
                        @endif
                    </div>
                </div>

                @if($item_type_id)
                <div class="flex items-center justify-between">
                    <x-input-label for="item_category_id" value="Kategori Barang" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <livewire:components.select-input wire:model.live="item_category_id"
                            :options="$itemCategories->pluck('name', 'id')" placeholder="-- Pilih Kategori Barang --"
                            :key="'category-select-' . $warehouse_id . '-' . $item_type_id" />
                        <x-input-error :messages="$errors->get('item_category_id')" class="mt-2" />
                    </div>
                </div>
                @endif

                @if($item_category_id)
                <div class="flex items-center justify-between">
                    <x-input-label for="item_id" value="Spesifikasi" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <livewire:components.select-input wire:model.live="item_id"
                            :options="$availableItems->mapWithKeys(fn($item) => [$item->id => $item->spec . ' (Stok: ' . ($item->stocks->where('warehouse_id', $warehouse_id)->first()?->qty ?? 0) . ' ' . ($item->category->unit->name ?? '') . ')'])"
                            placeholder="-- Pilih Spesifikasi --"
                            :key="'item-select-' . $warehouse_id . '-' . $item_category_id" />
                        <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                    </div>
                </div>
                @endif

                @if($item_id)
                <div class="flex items-center justify-between">
                    <x-input-label for="qty_request" value="Jumlah" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <div class="relative">
                            @php
                            $selectedItem = $availableItems->firstWhere('id', $item_id);
                            $unit = $selectedItem?->category?->unit?->name ?? '';
                            $maxStock = $selectedItem ? ($selectedItem->stocks->where('warehouse_id',
                            $warehouse_id)->first()?->qty ?? 0) : 0;
                            $placeholderText = $maxStock > 0 ? "Maks: " . number_format($maxStock, 2) : "0";
                            @endphp
                            <x-text-input id="qty_request" wire:model="qty_request" type="number" step="0.01" min="0.01"
                                max="{{ $maxStock > 0 ? $maxStock : '' }}" class="w-full pr-20"
                                placeholder="{{ $placeholderText }}" />
                            @if ($item_id && $unit)
                            <span
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 pointer-events-none">
                                {{ $unit }}
                            </span>
                            @endif
                        </div>
                        @if($item_id && $maxStock > 0)
                        <p class="mt-1 text-xs text-gray-500">Stok tersedia: {{ number_format($maxStock, 2) }}
                            {{ $unit }}</p>
                        @endif
                        <x-input-error :messages="$errors->get('qty_request')" class="mt-2" />
                    </div>
                </div>
                @endif

                <div class="flex justify-end">
                    <x-button wire:click="addItem">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Item
                    </x-button>
                </div>
                @endif
            </div>
        </x-card>

        <x-card title="Daftar Barang">
            @if (count($items) > 0)
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                    <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Barang</th>
                            <th scope="col" class="px-6 py-3">Spesifikasi</th>
                            <th scope="col" class="px-6 py-3">Jumlah</th>
                            <th scope="col" class="px-6 py-3">Stok Tersedia</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $index => $item)
                        <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                            <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                {{ $item['item_category'] }}</td>
                            <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                {{ $item['item_spec'] }}</td>
                            <td class="px-6 py-4 text-right">
                                {{ number_format($item['qty_request'], 2) }} <span class="font-medium">{{
                                    $item['item_unit'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="@if ($item['stock_available'] < $item['qty_request']) text-red-600 font-semibold @else text-green-600 @endif">
                                    {{ number_format($item['stock_available'], 2) }} {{ $item['item_unit'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <x-button variant="danger" type="button" wire:click="removeItem({{ $index }})">
                                    <i class="fa-solid fa-trash"></i>
                                </x-button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                    <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                        <tr>
                            <th scope="col" class="px-6 py-3">No</th>
                            <th scope="col" class="px-6 py-3">Barang</th>
                            <th scope="col" class="px-6 py-3">Spesifikasi</th>
                            <th scope="col" class="px-6 py-3">Jumlah</th>
                            <th scope="col" class="px-6 py-3">Stok Tersedia</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-primary-200">
                            <td colspan="6"
                                class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap text-center">
                                Belum ada barang yang ditambahkan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </x-card>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('permintaan.nonRab.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Batal
            </a>
            <x-button type="submit">
                Simpan Permintaan
            </x-button>
        </div>
    </form>
    @else
    <div class="p-4 text-center text-amber-800 rounded-lg bg-amber-50" role="alert">
        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-lg font-semibold">Silakan isi informasi permintaan terlebih dahulu</p>
    </div>
    @endif

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="create" modelType="App\Models\RequestModel"
        category="lampiran_permintaan" label="Upload Lampiran" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-upload-lampiran'" />
</div>