<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan Transfer</div>
        </div>
        <div class="text-right flex gap-2 justify-end">
            <x-primary-button href="{{ route('transfer.permintaan.index') }}" wire:navigate>
                Kembali
            </x-primary-button>
        </div>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-2 gap-4">
            <x-card title="Informasi Transfer">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label for="sudin_pengirim_id" value="Sudin Peminta" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="sudin_pengirim_id"
                                :options="$sudins->pluck('name', 'id')" placeholder="-- Pilih Sudin Peminta --"
                                :key="'sudin-pengirim-select'" />
                            <x-input-error :messages="$errors->get('sudin_pengirim_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="sudin_penerima_id" value="Sudin Diminta" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="sudin_penerima_id"
                                :options="$sudins->pluck('name', 'id')" placeholder="-- Pilih Sudin Diminta --"
                                :key="'sudin-penerima-select'" />
                            <x-input-error :messages="$errors->get('sudin_penerima_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="tanggal_transfer" value="Tanggal Transfer" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="tanggal_transfer" wire:model="tanggal_transfer" type="date"
                                class="w-full" />
                            <x-input-error :messages="$errors->get('tanggal_transfer')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="notes" value="Keterangan" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <textarea id="notes" wire:model="notes" rows="5"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan keterangan (opsional)"></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Tambah Barang">
                <div class="space-y-4">
                    @if (session('error'))
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (!$sudin_penerima_id)
                        <div class="p-4 text-sm text-amber-800 rounded-lg bg-amber-50" role="alert">
                            Pilih Sudin Diminta terlebih dahulu untuk menambahkan barang
                        </div>
                    @else
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
                            <x-primary-button type="button" wire:click="addItem">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Item
                            </x-primary-button>
                        </div>
                    @endif
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
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
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
            <x-primary-button type="submit">
                Simpan Permintaan Transfer
            </x-primary-button>
        </div>
    </form>
</div>