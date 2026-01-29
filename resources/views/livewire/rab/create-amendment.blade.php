<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Adendum RAB</div>
            <div class="text-sm text-gray-600 mt-1">RAB: {{ $this->rab->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('rab.show', $this->rab->id) }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>



    <div class="grid grid-cols-2 gap-4">
        <x-card title="Informasi Adendum">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <x-input-label for="nomor" value="Nomor Adendum" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <input type="text" id="nomor" wire:model="nomor"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                            placeholder="Masukkan nomor adendum">
                        @error('nomor')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </x-card>

        <x-card title="Tambah Barang">
            <form wire:submit.prevent="saveItem">
                <div class="space-y-4">
                    @if ($errors->has('item_id') || $errors->has('jumlahBarang'))
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            @error('item_id') {{ $message }} @enderror
                            @error('jumlahBarang') {{ $message }} @enderror
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <x-input-label for="item_category_id" value="Kategori Barang" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="item_category_id"
                                :options="$itemCategories->pluck('name', 'id')" placeholder="-- Pilih Kategori --"
                                :key="'category-select-amendment'" />
                            <x-input-error :messages="$errors->get('item_category_id')" class="mt-2" />
                        </div>
                    </div>

                    @if($item_category_id)
                        <div class="flex items-center justify-between">
                            <x-input-label for="item_id" value="Spesifikasi Barang" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <livewire:components.select-input wire:model.live="item_id"
                                    :options="$availableItems->pluck('spec', 'id')" placeholder="-- Pilih Barang --"
                                    :key="'item-select-amendment'" />
                                <x-input-error :messages="$errors->get('item_id')" class="mt-2" />
                            </div>
                        </div>
                    @endif

                    @if($item_id)
                        <div class="flex items-center justify-between">
                            <x-input-label for="jumlahBarang" value="Jumlah" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input id="jumlahBarang" wire:model="jumlahBarang" type="number" step="0.01"
                                    min="0.01" class="w-full" placeholder="0.00" />
                                <x-input-error :messages="$errors->get('jumlahBarang')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="hargaSatuanBarang" value="Harga Satuan" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input id="hargaSatuanBarang" wire:model="hargaSatuanBarang" currency
                                    placeholder="Masukkan harga satuan" class="w-full" />
                                <x-input-error :messages="$errors->get('hargaSatuanBarang')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="ppnBarang" value="PPN" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <select id="ppnBarang" wire:model="ppnBarang"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                    <option value="0" selected>Sudah Termasuk PPN</option>
                                    <option value="11">11%</option>
                                    <option value="12">12%</option>
                                </select>
                                <x-input-error :messages="$errors->get('ppnBarang')" class="mt-2" />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <x-button type="button" wire:click="saveItem">
                        Tambah Item
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <x-card title="Daftar Barang" class="mt-4">
        <div class="space-y-4">
            <!-- Tabel Items -->
            @if (count($items) > 0)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                            <tr>
                                <th scope="col" class="px-6 py-3">Kode</th>
                                <th scope="col" class="px-6 py-3">Item</th>
                                <th scope="col" class="px-6 py-3">Diminta</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                <th scope="col" class="px-6 py-3">Harga</th>
                                <th scope="col" class="px-6 py-3">Subtotal</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                                    <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                        {{ $item['item_code'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>{{ $item['item_name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item['requested_qty'] > 0)
                                            <span class="font-medium text-blue-600">
                                                {{ number_format($item['requested_qty'], 2) }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $item['item_unit'] }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <input type="number" step="0.01" min="{{ $item['min_qty'] }}"
                                                wire:model.live="items.{{ $index }}.qty" wire:change="updateQty({{ $index }})"
                                                class="w-28 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-right">
                                            <span>{{ $item['item_unit'] }}</span>
                                        </div>
                                        @if ($item['min_qty'] > 0)
                                            <div class="text-xs text-red-600 mt-1">Min: {{ $item['min_qty'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" min="0" wire:model.live="items.{{ $index }}.price"
                                            wire:change="updateQty({{ $index }})"
                                            class="w-32 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-right">
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        Rp {{ number_format($item['subtotal'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($item['is_original'])
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                Original
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Baru
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if (!$item['is_original'])
                                            <x-button variant="danger" type="button" wire:click="removeItem({{ $index }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </x-button>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-primary-200">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right font-semibold">Total:</td>
                                <td colspan="3" class="px-6 py-3 font-semibold text-right">
                                    Rp {{ number_format(collect($items)->sum('subtotal'), 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                    Belum ada item. Gunakan form pencarian di atas untuk menambahkan item.
                </div>
            @endif

            @error('items')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>
    </x-card>

    <div class="mt-4 flex justify-end gap-2">
        <a href="{{ route('rab.show', $this->rab->id) }}" wire:navigate
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition">
            Batal
        </a>
        <button type="button" wire:click="save"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition">
            Simpan Adendum
        </button>
    </div>
</div>