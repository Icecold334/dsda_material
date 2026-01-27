<div class="">
    <x-modal name="adjustment-modal" maxWidth="2xl">
        <div class="p-4 flex flex-col items-start gap-4 w-full">
            <div class="flex items-center justify-between w-full">
                <h1 class="text-xl font-semibold">Penyesuaian Stok Barang</h1>
                <div x-data="{ fileCount: 0 }"
                     @file-count-updated.window="fileCount = $event.detail">
                    <x-secondary-button @click="$dispatch('open-modal', 'lampiran-adjustment-modal')" type="button">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Lampiran
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                              x-show="fileCount > 0"
                              x-text="fileCount">
                        </span>
                    </x-secondary-button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="w-full p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="w-full p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form wire:submit.prevent="save" class="space-y-4 w-full">
                            <!-- Error Message -->
                            @if ($errors->has('general'))
                                <div class="p-3 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">
                                    {{ $errors->first('general') }}
                                </div>
                            @endif

                <!-- Item Category Select -->
                <div class="flex items-center justify-between">
                    <x-input-label for="itemCategoryId" value="Nama Barang" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <livewire:components.select-input
                            wire:model.live="itemCategoryId"
                            :options="collect($categories)->mapWithKeys(fn($cat) => [$cat->id => $cat->name . ' (' . $cat->unit->name . ')'])"
                            placeholder="-- Pilih Nama Barang --"
                            :key="'category-select-' . $warehouseId" />
                        <x-input-error :messages="$errors->get('itemCategoryId')" class="mt-2" />
                    </div>
                </div>

                <!-- Item Spec Select -->
                @if ($itemCategoryId && count($items) > 0)
                    <div class="flex items-center justify-between">
                        <x-input-label for="itemId" value="Spesifikasi" />
                        <div class="mt-1 block w-full max-w-[500px]">
                            <livewire:components.select-input
                                wire:model="itemId"
                                :options="collect($items)->mapWithKeys(fn($item) => [$item->id => $item->spec . ' (Stok: ' . number_format($item->stocks->first()->qty ?? 0, 2) . ')'])"
                                placeholder="-- Pilih Spesifikasi --"
                                :key="'item-select-' . $itemCategoryId" />
                            <x-input-error :messages="$errors->get('itemId')" class="mt-2" />
                        </div>
                    </div>
                @endif

                @if ($itemCategoryId && count($items) == 0)
                    <div class="w-full p-3 text-sm text-yellow-800 bg-yellow-100 border border-yellow-200 rounded-lg">
                        Tidak ada stok untuk kategori <strong>{{ $selectedCategoryName }}</strong> di gudang ini.
                    </div>
                @endif

                <!-- Type Select -->
                <div class="flex items-center justify-between">
                    <x-input-label for="type" value="Jenis Penyesuaian" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <livewire:components.select-input
                            wire:model="type"
                            :options="['IN' => 'Tambah Stok', 'KURANGI' => 'Kurangi Stok']"
                            placeholder="-- Pilih Jenis --"
                            :key="'type-select'" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>

                <!-- Quantity Input -->
                <div class="flex items-center justify-between">
                    <x-input-label for="qty" value="Jumlah Perubahan" />
                    <div class="mt-1 block w-full max-w-[500px]">
                        <x-text-input id="qty" wire:model="qty" type="number" step="0.01" min="0.01"
                            class="w-full" placeholder="0.00" />
                        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                    </div>
                </div>

                <!-- Current Stock Info -->
                @if ($itemId)
                    @php
                        $selectedItem = $items->firstWhere('id', $itemId);
                        $currentQty = $selectedItem?->stocks->first()->qty ?? 0;
                        $changeQty = $type === 'IN' ? floatval($qty ?? 0) : -floatval($qty ?? 0);
                        $newQty = $currentQty + $changeQty;
                    @endphp
                    <div class="w-full p-3 rounded-lg bg-gray-50">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <div class="font-semibold text-gray-600">Stok Saat Ini</div>
                                <div class="text-lg font-bold">{{ number_format($currentQty, 2) }}</div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-600">Perubahan</div>
                                <div
                                    class="text-lg font-bold {{ $type === 'IN' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $type === 'IN' ? '+' : '-' }}{{ number_format(floatval($qty ?? 0), 2) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-600">Stok Setelah</div>
                                <div class="text-lg font-bold {{ $newQty < 0 ? 'text-red-600' : 'text-blue-600' }}">
                                    {{ number_format($newQty, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="w-full h-[1px] bg-slate-200"></div>
                <div class="flex justify-end gap-3 w-full">
                    <x-button variant="secondary" type="button" x-on:click="$dispatch('close-modal', 'adjustment-modal')">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        Simpan Penyesuaian
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Lampiran -->
    <livewire:components.document-upload 
        mode="create"
        modelType="App\Models\StockTransaction"
        category="lampiran_penyesuaian"
        label="Upload Lampiran Penyesuaian Stok"
        :multiple="true"
        accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-adjustment-modal"
        :key="'doc-upload-adjustment-' . $warehouseId" />
</div>