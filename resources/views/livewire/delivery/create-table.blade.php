<div class="space-y-4">
    <x-card title="Tambah Barang" class="space-y-4">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-1/3 font-semibold bg-gray-50 px-4 py-2 rounded">
                    Nama Barang
                </div>
                <div class="w-2/3">
                    <livewire:components.select-input wire:model.live="itemCategory"
                        :options="$itemCategories->pluck('name', 'id')" placeholder="Pilih Barang" />
                </div>
                <div class="w-1/3 font-semibold bg-gray-50 px-4 py-2 rounded">
                    Spesifikasi
                </div>
                <div class="w-2/3">
                    <livewire:components.select-input wire:model.live="item" :disabled="!$itemCategory"
                        wire:key="item-select-{{ $itemCategory }}" :options="$items->pluck('spec', 'id')"
                        placeholder="Pilih Spesifikasi" />
                </div>
                <div class="w-1/3 font-semibold bg-gray-50 px-4 py-2 rounded">
                    Jumlah
                    @if ($maxQty > 0)
                        <div class="text-xs font-bold text-gray-500">
                            Max: {{ $maxQty }} {{ $unit }}
                        </div>
                    @endif
                </div>

                <div class="w-2/3">
                    <div class="flex">
                        <input type="number" wire:model.live.debounce.500ms='qty'
                            class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                            @disabled(!$item) placeholder="Jumlah Pengiriman" max="{{ $maxQty }}" required>

                        <div
                            class="text-center rounded-none rounded-e-lg bg-gray-200 font-semibold Satuan border border-gray-300 text-gray-900 block w-20 text-sm p-2.5">
                            {{ $unit }}
                        </div>
                    </div>
                </div>
                <div class="w-1/12">
                    <button type="submit" @disabled($disablAdd)
                        class="{{ !$disablAdd ? 'bg-primary-700 hover:bg-primary-800' : 'bg-gray-400 cursor-not-allowed' }} text-center text-white focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 focus:outline-none">Tambah</button>
                </div>
            </div>
        </form>
    </x-card>
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
                                <button type="button" wire:click="removeItem({{ $loop->index }})"
                                    class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm"><i
                                        class="fa-solid fa-trash"></i></button>
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
    </x-card>
</div>