<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan Transfer</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            @if($informationFilled)
                <x-secondary-button @click="$dispatch('open-modal', 'transfer-information-modal')" type="button">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi
                </x-secondary-button>
            @endif
            <x-button variant="secondary" href="{{ route('transfer.permintaan.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    @if(!$informationFilled)
        <div class="p-6 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-amber-800">Silahkan lengkapi informasi transfer terlebih dahulu pada modal.</p>
            </div>
        </div>
    @else
        <form wire:submit="save">
            <div class="grid grid-cols-2 gap-4">
                <x-card title="Tambah Barang">
                    <div class="space-y-4">
                        @if (session('error'))
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <x-input-label for="item_category_id" value="Kategori Barang" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <livewire:components.select-input wire:model.live="item_category_id"
                                    :options="$itemCategories->pluck('name', 'id')"
                                    placeholder="-- Pilih Kategori Barang --" :key="'category-select-' . $sudin_penerima_id" />
                                <x-input-error :messages="$errors->get('item_category_id')" class="mt-2" />
                            </div>
                        </div>

                        @if($item_category_id)
                            <div class="flex items-center justify-between">
                                <x-input-label for="item_id" value="Spesifikasi" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <livewire:components.select-input wire:model.live="item_id"
                                        :options="$availableItems->mapWithKeys(fn($item) => [$item->id => $item->spec])"
                                        placeholder="-- Pilih Spesifikasi --" :key="'item-select-' . $sudin_penerima_id . '-' . $item_category_id" />
                                    <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                                </div>
                            </div>
                        @endif

                        @if($item_id)
                            <div class="flex items-center justify-between">
                                <x-input-label for="qty" value="Jumlah" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <div class="relative">
                                        @php
                                            $selectedItem = $availableItems->firstWhere('id', $item_id);
                                            $unit = $selectedItem?->category?->unit?->name ?? '';
                                        @endphp
                                        <x-text-input id="qty" wire:model="qty" type="number" step="0.01" min="0.01"
                                            class="w-full pr-20" placeholder="Masukkan jumlah" />
                                        @if ($item_id && $unit)
                                            <span
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 pointer-events-none">
                                                {{ $unit }}
                                            </span>
                                        @endif
                                    </div>
                                    <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <x-input-label for="item_notes" value="Catatan Item" />
                                <div class="mt-1 block w-full max-w-[500px]">
                                    <textarea id="item_notes" wire:model="item_notes" rows="2"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                        placeholder="Catatan untuk item ini (opsional)"></textarea>
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
                    </div>
                </x-card>
            </div>

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
                                    <th scope="col" class="px-6 py-3">Stok di Sudin Diminta</th>
                                    <th scope="col" class="px-6 py-3">Catatan</th>
                                    <th scope="col" class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                    <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                            {{ $item['item_category'] }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                            {{ $item['item_spec'] }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{ number_format($item['qty'], 2) }} <span
                                                class="font-medium">{{ $item['item_unit'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-gray-600">
                                            {{ number_format($item['stock_available'], 2) }} {{ $item['item_unit'] }}
                                        </td>
                                        <td class="px-6 py-4">{{ $item['notes'] ?: '-' }}</td>
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
                                    <th scope="col" class="px-6 py-3">Stok di Sudin Diminta</th>
                                    <th scope="col" class="px-6 py-3">Catatan</th>
                                    <th scope="col" class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b border-primary-200">
                                    <td colspan="7"
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
                <a href="{{ route('transfer.permintaan.index') }}" wire:navigate
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
                <x-button type="submit">
                    Simpan Permintaan Transfer
                </x-button>
            </div>
        </form>
    @endif

    <!-- Modal Components -->
    <livewire:components.transfer-information-modal mode="create" :key="'transfer-info-modal'" />
</div>