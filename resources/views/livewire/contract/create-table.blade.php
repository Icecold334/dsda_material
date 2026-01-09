<div class="space-y-4">
    <x-card title="Tambah Barang" class="space-y-4">
        <div class="grid md:grid-cols-2 w-full md:gap-4">
            <table>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">Nama Barang</td>
                    <td class="px-4 py-2"><input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Nama Barang" /></td>
                </tr>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">Spesifikasi</td>
                    <td class="px-4 py-2">
                        <textarea id="message" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                            placeholder="Spesifikasi Barang"></textarea>
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">Jumlah</td>
                    <td class="px-4 py-2"><input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Jumlah Barang" /></td>
                </tr>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">Satuan</td>
                    <td class="px-4 py-2">
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Satuan Barang" />
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">Harga Satuan</td>
                    <td class="px-4 py-2"><input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            placeholder="Harga Satuan Barang" />
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold bg-gray-50 px-4 py-2">PPN</td>
                    <td class="px-4 py-2">
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option selected>Sudah Termasuk PPN</option>
                            <option value="11">11%</option>
                            <option value="12">12%</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="text-right ">
            <button type="button"
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Tambah</button>
        </div>
    </x-card>
    <x-card title="Daftar Barang">

    </x-card>
</div>