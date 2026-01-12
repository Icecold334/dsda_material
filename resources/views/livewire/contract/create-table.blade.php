<div class="space-y-4">
    <x-card title="Tambah Barang" class="space-y-4">
        <form wire:submit.prevent="save">
            <div class="grid md:grid-cols-2 w-full md:gap-4">
                <table>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">Nama Barang</td>
                        <td class="px-4 py-2">
                            <livewire:components.select-input wire:model="namaBarang" :freetext="true"
                                :options="$barangs->pluck('name', 'id')" placeholder="Nama Barang" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">Spesifikasi</td>
                        <td class="px-4 py-2">
                            <textarea id="message" rows="4" wire:model="spesifikasiBarang"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                placeholder="Spesifikasi Barang"></textarea>
                        </td>
                    </tr>

                </table>
                <table>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">Jumlah</td>
                        <td class="px-4 py-2"><input type="text" wire:model="jumlahBarang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                placeholder="Jumlah Barang" /></td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">Satuan</td>
                        <td class="px-4 py-2">
                            @dump($satuanBarang)
                            <livewire:components.select-input wire:model="satuanBarang" :freetext="true"
                                :options="$units->pluck('name', 'id')" placeholder="Satuan Barang" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">Harga Satuan</td>
                        <td class="px-4 py-2">
                            @dump($hargaSatuanBarang)
                            <x-text-input wire:model="hargaSatuanBarang" currency placeholder="Harga Satuan Barang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 " />
                            {{-- <input type="text" wire:model='hargaSatuanBarang'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                placeholder="Harga Satuan Barang" /> --}}
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold bg-gray-50 px-4 py-2">PPN</td>
                        <td class="px-4 py-2">
                            <select wire:model="ppnBarang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="0" selected>Sudah Termasuk PPN</option>
                                <option value="11">11%</option>
                                <option value="12">12%</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="text-right ">
                <button type="submit"
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Tambah</button>
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
                            <button type="button" wire:click="removeItem({{ $loop->index }})"
                                class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm"><i
                                    class="fa-solid fa-trash"></i></button>
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