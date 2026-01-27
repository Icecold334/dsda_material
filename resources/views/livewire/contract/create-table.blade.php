<div class="space-y-4">
    <div class="grid-cols-2 grid">
        <x-card title="Tambah Barang">
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label for="namaBarang" value="Nama Barang" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model="namaBarang" :freetext="true"
                                :options="$barangs->pluck('name', 'id')" placeholder="-- Pilih Nama Barang --"
                                :key="'nama-barang-select'" />
                            <x-input-error :messages="$errors->get('namaBarang')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="spesifikasiBarang" value="Spesifikasi" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <textarea id="spesifikasiBarang" rows="4" wire:model="spesifikasiBarang"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                placeholder="Masukkan spesifikasi barang"></textarea>
                            <x-input-error :messages="$errors->get('spesifikasiBarang')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="jumlahBarang" value="Jumlah" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <x-text-input id="jumlahBarang" wire:model="jumlahBarang" type="number" step="0.01"
                                class="w-full" placeholder="Masukkan jumlah barang" />
                            <x-input-error :messages="$errors->get('jumlahBarang')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-input-label for="satuanBarang" value="Satuan" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input wire:model="satuanBarang" :freetext="true"
                                :options="$units->pluck('name', 'id')" placeholder="-- Pilih Satuan --"
                                :key="'satuan-barang-select'" />
                            <x-input-error :messages="$errors->get('satuanBarang')" class="mt-2" />
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
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button type="submit">
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
                            Harga Satuan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            PPN
                        </th>
                        <th scope="col" class="px-6 py-3">
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($listBarang as $item)
                        <tr class="even:bg-primary-100 odd:bg-primary-50 border-primary-200">
                            <td class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap">
                                {{ $item['namaBarang'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item['spesifikasiBarang'] }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                {{ $item['jumlahBarang'] }} <span class="font-medium">{{ $item['satuanBarang'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ number_format($item['hargaSatuanBarang'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $item['ppnBarang'] == 0 ? 'Sudah Termasuk PPN' : $item['ppnBarang'] . '%' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{-- Tombol Hapus Badge --}}
                                <x-button variant="danger" wire:click="removeItem({{ $loop->index }})"><i
                                        class="fa-solid fa-trash"></i></x-button>
                            </td>

                        </tr>
                    @empty
                        <tr class="bg-white border-b  border-primary-200">
                            <td colspan="6" class="px-6 py-4 font-medium text-primary-900 whitespace-nowrap text-center">
                                Belum ada barang yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>