<div>
    <x-modal :name="$modalId" :show="$showModal" :dismissable="!$isFirstTime || $mode === 'show'" maxWidth="2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ $mode === 'create' ? 'Informasi Pengiriman' : 'Detail Informasi Pengiriman' }}
                </h2>
                @if(!$isFirstTime || $mode === 'show')
                    <button type="button" x-on:click="$dispatch('close-modal', '{{ $modalId }}')" wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            @error('modal')
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <form wire:submit.prevent="saveInformation">
                <div class="space-y-4">
                    @if($mode === 'show' && $delivery)
                        <!-- Informasi Lengkap untuk Show Mode -->
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Nomor Pengiriman" />
                            <div class="col-span-2 text-gray-700">
                                {{ $delivery->nomor }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Nomor Kontrak" />
                            <div class="col-span-2 text-gray-700">
                                {{ $delivery->contract->nomor }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Gudang" />
                            <div class="col-span-2 text-gray-700">
                                {{ $delivery->warehouse->name }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Sudin" />
                            <div class="col-span-2 text-gray-700">
                                {{ $delivery->sudin->name }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Tanggal Pengiriman" />
                            <div class="col-span-2 text-gray-700">
                                {{ \Carbon\Carbon::parse($delivery->tanggal_delivery)->format('d F Y') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Status" />
                            <div class="col-span-2">
                                <span
                                    class="bg-{{ $delivery->status_color }}-600 text-{{ $delivery->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $delivery->status_text }}
                                </span>
                            </div>
                        </div>


                    @elseif($mode === 'show')
                        <!-- Informasi Singkat untuk Show Mode tanpa delivery object -->
                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Nomor Kontrak" />
                            <div class="col-span-2 text-gray-700">
                                {{ $nomor_kontrak ?: '-' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Gudang" />
                            <div class="col-span-2 text-gray-700">
                                {{ $warehouse_name ?: '-' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 items-center">
                            <x-input-label value="Tanggal Pengiriman" />
                            <div class="col-span-2 text-gray-700">
                                {{ $tanggal_kirim ? \Carbon\Carbon::parse($tanggal_kirim)->format('d F Y') : '-' }}
                            </div>
                        </div>
                    @else
                        <!-- Create Mode -->
                        <!-- Gudang -->
                        <div class="grid grid-cols-3 gap-4 items-start">
                            <x-input-label for="warehouse_id" value="Gudang" class="pt-2" />
                            <div class="col-span-2">
                                <livewire:components.select-input wire:model.live="warehouse_id"
                                    :options="$warehouses->pluck('name', 'id')" placeholder="-- Pilih Gudang --"
                                    :key="'delivery-warehouse-select'" />
                                <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Tanggal Pengiriman -->
                        <div class="grid grid-cols-3 gap-4 items-start">
                            <x-input-label for="tanggal_kirim" value="Tanggal Pengiriman" class="pt-2" />
                            <div class="col-span-2">
                                <x-text-input id="tanggal_kirim" wire:model="tanggal_kirim" type="date" class="w-full" />
                                <x-input-error :messages="$errors->get('tanggal_kirim')" class="mt-2" />
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    @if($mode === 'create')
                        <x-button type="submit">
                            Simpan
                        </x-button>
                    @else
                        <x-secondary-button type="button" x-on:click="$dispatch('close-modal', '{{ $modalId }}')"
                            wire:click="closeModal">
                            Tutup
                        </x-secondary-button>
                    @endif
                </div>
            </form>
        </div>
    </x-modal>
</div>