<div>
    <x-modal name="delivery-info-modal" maxWidth="4xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">
                    Informasi Pengiriman
                </h2>
                <button type="button" @click="$dispatch('close-modal', 'delivery-info-modal')"
                    wire:click="closeModal"
                    class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if(session()->has('message'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if ($canEditDelivery)
                <form wire:submit="saveDeliveryInfo" class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="nopol" value="Nomor Polisi" class="pt-2" />
                        <div class="col-span-2">
                            <x-text-input id="nopol" wire:model="nopol" type="text" class="w-full"
                                placeholder="Masukkan nomor polisi" />
                            <x-input-error :messages="$errors->get('nopol')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="driver_id" value="Driver" class="pt-2" />
                        <div class="col-span-2">
                            <livewire:components.select-input wire:model="driver_id" :options="$drivers->pluck('name', 'id')"
                                placeholder="-- Pilih Driver --" :key="'driver-modal-select-' . $permintaan->id" />
                            <x-input-error :messages="$errors->get('driver_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-start">
                        <x-input-label for="security_id" value="Security" class="pt-2" />
                        <div class="col-span-2">
                            <livewire:components.select-input wire:model="security_id" :options="$securities->pluck('name', 'id')"
                                placeholder="-- Pilih Security --" :key="'security-modal-select-' . $permintaan->id" />
                            <x-input-error :messages="$errors->get('security_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div x-data="{
                                    canvas: null,
                                    ctx: null,
                                    isDrawing: false,
                                    init() {
                                        this.canvas = this.$refs.signatureCanvas;
                                        this.ctx = this.canvas.getContext('2d');
                                        this.ctx.strokeStyle = '#000000';
                                        this.ctx.lineWidth = 2;
                                        this.ctx.lineCap = 'round';
                                    },
                                    getCoordinates(e) {
                                        const rect = this.canvas.getBoundingClientRect();
                                        const scaleX = this.canvas.width / rect.width;
                                        const scaleY = this.canvas.height / rect.height;
                                        return {
                                            x: (e.clientX - rect.left) * scaleX,
                                            y: (e.clientY - rect.top) * scaleY
                                        };
                                    },
                                    startDrawing(e) {
                                        this.isDrawing = true;
                                        const coords = this.getCoordinates(e);
                                        this.ctx.beginPath();
                                        this.ctx.moveTo(coords.x, coords.y);
                                    },
                                    draw(e) {
                                        if (!this.isDrawing) return;
                                        const coords = this.getCoordinates(e);
                                        this.ctx.lineTo(coords.x, coords.y);
                                        this.ctx.stroke();
                                    },
                                    stopDrawing() {
                                        this.isDrawing = false;
                                        this.saveSignature();
                                    },
                                    clearCanvas() {
                                        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                        $wire.set('ttd_driver', '');
                                    },
                                    saveSignature() {
                                        const dataURL = this.canvas.toDataURL('image/png');
                                        $wire.set('ttd_driver', dataURL);
                                    }
                                }">
                            <x-input-label value="Tanda Tangan Driver" class="mb-2" />
                            <div class="border-2 border-gray-300 rounded-md p-2 bg-white">
                                <canvas x-ref="signatureCanvas" width="400" height="150"
                                    class="border border-gray-300 rounded cursor-crosshair w-full" @mousedown="startDrawing($event)"
                                    @mousemove="draw($event)" @mouseup="stopDrawing()" @mouseleave="stopDrawing()"></canvas>
                                <x-secondary-button type="button" @click="clearCanvas()" class="mt-2">
                                    Hapus
                                </x-secondary-button>
                            </div>
                            <x-input-error :messages="$errors->get('ttd_driver')" class="mt-2" />
                        </div>

                        <div x-data="{
                                    canvas: null,
                                    ctx: null,
                                    isDrawing: false,
                                    init() {
                                        this.canvas = this.$refs.signatureCanvas;
                                        this.ctx = this.canvas.getContext('2d');
                                        this.ctx.strokeStyle = '#000000';
                                        this.ctx.lineWidth = 2;
                                        this.ctx.lineCap = 'round';
                                    },
                                    getCoordinates(e) {
                                        const rect = this.canvas.getBoundingClientRect();
                                        const scaleX = this.canvas.width / rect.width;
                                        const scaleY = this.canvas.height / rect.height;
                                        return {
                                            x: (e.clientX - rect.left) * scaleX,
                                            y: (e.clientY - rect.top) * scaleY
                                        };
                                    },
                                    startDrawing(e) {
                                        this.isDrawing = true;
                                        const coords = this.getCoordinates(e);
                                        this.ctx.beginPath();
                                        this.ctx.moveTo(coords.x, coords.y);
                                    },
                                    draw(e) {
                                        if (!this.isDrawing) return;
                                        const coords = this.getCoordinates(e);
                                        this.ctx.lineTo(coords.x, coords.y);
                                        this.ctx.stroke();
                                    },
                                    stopDrawing() {
                                        this.isDrawing = false;
                                        this.saveSignature();
                                    },
                                    clearCanvas() {
                                        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                        $wire.set('ttd_security', '');
                                    },
                                    saveSignature() {
                                        const dataURL = this.canvas.toDataURL('image/png');
                                        $wire.set('ttd_security', dataURL);
                                    }
                                }">
                            <x-input-label value="Tanda Tangan Security" class="mb-2" />
                            <div class="border-2 border-gray-300 rounded-md p-2 bg-white">
                                <canvas x-ref="signatureCanvas" width="400" height="150"
                                    class="border border-gray-300 rounded cursor-crosshair w-full" @mousedown="startDrawing($event)"
                                    @mousemove="draw($event)" @mouseup="stopDrawing()" @mouseleave="stopDrawing()"></canvas>
                                <x-secondary-button type="button" @click="clearCanvas()" class="mt-2">
                                    Hapus
                                </x-secondary-button>
                            </div>
                            <x-input-error :messages="$errors->get('ttd_security')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button type="button" @click="$dispatch('close-modal', 'delivery-info-modal')" 
                            wire:click="closeModal">
                            Batal
                        </x-secondary-button>
                        <x-button type="submit">
                            Simpan Informasi Pengiriman
                        </x-button>
                    </div>
                </form>
            @else
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <x-input-label value="Nomor Polisi" />
                        <div class="col-span-2 text-gray-700">
                            {{ $permintaan->nopol ?? "-" }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-center">
                        <x-input-label value="Driver" />
                        <div class="col-span-2 text-gray-700">
                            {{ $permintaan->driver?->name ?? "-" }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 items-center">
                        <x-input-label value="Security" />
                        <div class="col-span-2 text-gray-700">
                            {{ $permintaan->security?->name ?? "-" }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Tanda Tangan Driver" class="mb-2" />
                            <div class="border-2 border-gray-300 rounded-md p-2 bg-gray-50">
                                @php
                                    $driverSignature = $permintaan->documents()->where('category', 'ttd_driver')->first();
                                @endphp
                                @if ($driverSignature)
                                    <img src="{{ Storage::url($driverSignature->file_path) }}" alt="TTD Driver"
                                        class="w-full h-[150px] object-contain border border-gray-300 rounded bg-white">
                                @else
                                    <div
                                        class="w-full h-[150px] flex items-center justify-center border border-gray-300 rounded bg-white text-gray-400">
                                        Tidak ada tanda tangan
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Tanda Tangan Security" class="mb-2" />
                            <div class="border-2 border-gray-300 rounded-md p-2 bg-gray-50">
                                @php
                                    $securitySignature = $permintaan->documents()->where('category', 'ttd_security')->first();
                                @endphp
                                @if ($securitySignature)
                                    <img src="{{ Storage::url($securitySignature->file_path) }}" alt="TTD Security"
                                        class="w-full h-[150px] object-contain border border-gray-300 rounded bg-white">
                                @else
                                    <div
                                        class="w-full h-[150px] flex items-center justify-center border border-gray-300 rounded bg-white text-gray-400">
                                        Tidak ada tanda tangan
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" @click="$dispatch('close-modal', 'delivery-info-modal')" 
                            wire:click="closeModal">
                            Tutup
                        </x-secondary-button>
                    </div>
                </div>
            @endif
        </div>
    </x-modal>
</div>
