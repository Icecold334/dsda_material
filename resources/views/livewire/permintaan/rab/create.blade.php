<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Buat Permintaan dengan RAB</div>
        </div>
        <div class="text-right flex gap-2 justify-end" x-data="{ fileCount: 0 }"
            @file-count-updated.window="fileCount = $event.detail">
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
        </div>
    </x-modal>

    @if (session('rab_found'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('rab_found') }}
        </div>
    @endif

    @if ($rab)
        <form wire:submit.prevent="validateForm">
            <div class="grid grid-cols-1 gap-4">
                <x-card title="Informasi Permintaan">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <x-input-label for="nomor" value="Nomor SPB" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input id="nomor" wire:model="nomor" placeholder="Masukkan nomor SPB"
                                    type="text" class="w-full" />
                                <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="name" value="Nama Permintaan" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input id="name" wire:model="name" type="text" class="w-full bg-gray-100"
                                    placeholder="Otomatis dari RAB" disabled />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="sudin_id" value="Sudin" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input type="text" class="w-full bg-gray-100"
                                    value="{{ $rab?->sudin?->name ?? '-' }}" disabled />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="warehouse_id" value="Gudang" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <div class="flex gap-2">
                                    @if ($warehouse_id)
                                        @php
                                            $selectedWarehouse = $warehousesWithStock->firstWhere('id', $warehouse_id);
                                        @endphp
                                        <x-text-input type="text" class="w-full bg-gray-100"
                                            value="{{ $selectedWarehouse['name'] ?? '-' }}" disabled />
                                    @else
                                        <x-text-input type="text" class="w-full bg-gray-100"
                                            placeholder="-- Pilih Gudang --" disabled />
                                    @endif
                                    <x-button type="button" wire:click="openWarehouseModal">
                                        Pilih
                                    </x-button>
                                </div>
                                <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="tanggal_permintaan" value="Tanggal Permintaan" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <x-text-input id="tanggal_permintaan" wire:model="tanggal_permintaan" type="date"
                                    class="w-full" />
                                <x-input-error :messages="$errors->get('tanggal_permintaan')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="district_id" value="Lokasi" />
                            <div class="input w-full max-w-[500px] flex flex-col gap-4">
                                <div class="grid-cols-2 flex gap-4">
                                    <div class="w-full">
                                        <x-text-input type="text" class="w-full bg-gray-100"
                                            value="{{ $rab?->district?->name ?? '-' }}" disabled />
                                    </div>
                                    <div class="w-full">
                                        <x-text-input type="text" class="w-full bg-gray-100"
                                            value="{{ $rab?->subdistrict?->name ?? '-' }}" disabled />
                                    </div>
                                </div>
                                <textarea id="address" wire:model="address" rows="3"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-gray-100"
                                    placeholder="Otomatis dari RAB" disabled></textarea>
                                <x-input-error :messages="$errors->get('address')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="panjang" value="Dimensi" />
                            <div class="w-full max-w-[500px] grid grid-cols-3 gap-4">
                                <div class="w-full">
                                    <x-text-input id="panjang" wire:model="panjang" type="text"
                                        class="w-full bg-gray-100" placeholder="0" disabled />
                                </div>
                                <div class="w-full">
                                    <x-text-input id="lebar" wire:model="lebar" type="text"
                                        class="w-full bg-gray-100" placeholder="0" disabled />
                                </div>
                                <div class="w-full">
                                    <x-text-input id="tinggi" wire:model="tinggi" type="text"
                                        class="w-full bg-gray-100" placeholder="0" disabled />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <x-input-label for="notes" value="Keterangan" />
                            <div class="mt-1 block w-full max-w-[500px]">
                                <textarea id="notes" wire:model="notes" rows="3"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                    placeholder="Masukkan keterangan (opsional)"></textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Daftar Barang dari RAB -->
                <x-card title="Daftar Barang dari RAB">
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
                                                <input type="number"
                                                    wire:model.live="items.{{ $index }}.qty_request"
                                                    step="0.01" min="0" max="{{ $item['max_qty'] }}"
                                                    class="w-24 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                                    {{ !$warehouse_id || $item['max_qty'] <= 0 ? 'disabled' : '' }} />
                                                <span class="text-gray-600">{{ $item['item_unit'] }}</span>
                                            </div>
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
            </div>

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

        <!-- Modal Lampiran -->
        <livewire:components.document-upload mode="create" modelType="App\Models\RequestModel"
            category="lampiran_permintaan" label="Upload Lampiran" :multiple="true" accept="image/*,.pdf,.doc,.docx"
            modalId="lampiran-modal" :key="'doc-upload-lampiran'" />

        <!-- Modal Pilih Gudang -->
        <livewire:permintaan.rab.warehouse-selection-modal :key="'warehouse-modal-' . ($rab?->id ?? 'empty')" />
    @endif
</div>

@push('scripts')
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('validation-passed-create', () => {
                showConfirm({
                    title: "Konfirmasi Simpan Permintaan",
                    text: "Apakah anda yakin ingin menyimpan permintaan ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Permintaan...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-permintaan-rab');
                    }
                });
            });
        });
    </script>
@endpush
