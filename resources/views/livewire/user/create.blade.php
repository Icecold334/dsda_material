<x-modal name="create-user" focusable>
    <form wire:submit.prevent="validateForm" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Pengguna Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full"
                    placeholder="email@example.com" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" wire:model="password" type="password" class="mt-1 block w-full"
                    placeholder="Minimal 8 karakter" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                <x-text-input id="password_confirmation" wire:model="password_confirmation" type="password"
                    class="mt-1 block w-full" placeholder="Ulangi password" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
            </div>

            <div>
                <x-input-label for="division_id" value="Divisi" />
                <livewire:components.select-input wire:model="division_id" :options="$divisions->pluck('name', 'id')"
                    placeholder="-- Pilih Divisi --" :key="'division-select'" />
            </div>

            <div>
                <x-input-label for="position_id" value="Jabatan" />
                <livewire:components.select-input wire:model="position_id" :options="$positions->pluck('name', 'id')"
                    placeholder="-- Pilih Jabatan --" :key="'position-select'" />
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
                    $wire.set('ttd', '');
                },
                saveSignature() {
                    const dataURL = this.canvas.toDataURL('image/png');
                    $wire.set('ttd', dataURL);
                }
            }">
                <x-input-label value="Tanda Tangan" class="mb-2" />
                <div class="border-2 border-gray-300 rounded-md p-2 bg-white">
                    <canvas x-ref="signatureCanvas" width="400" height="200"
                        class="border border-gray-300 rounded cursor-crosshair w-full" @mousedown="startDrawing($event)"
                        @mousemove="draw($event)" @mouseup="stopDrawing()" @mouseleave="stopDrawing()"></canvas>
                    <x-secondary-button type="button" @click="clearCanvas()" class="mt-2">
                        Hapus Tanda Tangan
                    </x-secondary-button>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'create-user')">
                Batal
            </x-secondary-button>
            <x-button type="submit">
                Simpan
            </x-button>
        </div>
    </form>
</x-modal>

@push('scripts')
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('validation-passed-create', () => {
                showConfirm({
                    title: "Konfirmasi Simpan Pengguna",
                    text: "Apakah anda yakin ingin menambahkan pengguna ini?",
                    type: "question",
                    confirmButtonText: "Ya, Simpan",
                    cancelButtonText: "Batal",
                    onConfirm: () => {
                        Swal.fire({
                            title: "Menyimpan Pengguna...",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        Livewire.dispatch('confirm-save-user');
                    }
                });
            });
        });
    </script>
@endpush