<div class="space-y-4">
    <div class="grid grid-cols-2">
        <x-card title="Tambah Barang">
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center justify-between">
                        <x-input-label for="itemCategory" value="Nama Barang" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="itemCategory"
                                :options="$itemCategories->pluck('name', 'id')" placeholder="-- Pilih Barang --"
                                :key="'item-category-select'" />
                            <x-input-error :messages="$errors->get('itemCategory')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="item" value="Spesifikasi" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model.live="item" :disabled="!$itemCategory"
                                wire:key="item-select-{{ $itemCategory }}" :options="$items->pluck('spec', 'id')"
                                placeholder="-- Pilih Spesifikasi --" />
                            <x-input-error :messages="$errors->get('item')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="qty" value="Jumlah" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <div class="relative">
                                <x-text-input id="qty" wire:model.live.debounce.500ms="qty" type="number" step="0.01"
                                    :disabled="!$item" class="w-full pr-20" placeholder="Masukkan jumlah"
                                    max="{{ $maxQty }}" required />
                                @if ($unit)
                                    <span
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 pointer-events-none">
                                        {{ $unit }}
                                    </span>
                                @endif
                            </div>
                            @if($item && $maxQty > 0)
                                <p class="mt-1 text-xs text-gray-500">Maksimal: {{ number_format($maxQty, 2) }} {{ $unit }}
                                </p>
                            @endif
                            <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button type="submit" :disabled="$disablAdd">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Barang
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
    <x-card title="Daftar Barang">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-black shadow-lg">
                <thead class="text-xs text-gray-700 uppercase bg-primary-200 text-center">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nama Barang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Spesifikasi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jumlah
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($listBarang as $barang)
                        <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                            <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                {{ $barang['namaBarang'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $barang['spesifikasiBarang'] }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ $barang['qty'] }} <span class="font-medium">{{ $barang['unit'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{-- Tombol Hapus Badge --}}
                                <x-button variant="danger" type="button" wire:click="removeItem({{ $loop->index }})"><i
                                        class="fa-solid fa-trash"></i></x-button>
                            </td>

                        </tr>
                    @empty
                        <tr class="bg-white border-b  border-primary-200">
                            <td colspan="4" class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap text-center">
                                Belum ada barang yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($listBarang) > 0)
            <div class="mt-6 flex justify-end">
                <x-button type="button" wire:click="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Simpan Pengiriman
                    </span>
                    <span wire:loading wire:target="submit">
                        <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Menyimpan...
                    </span>
                </x-button>
            </div>
        @endif
    </x-card>
</div>