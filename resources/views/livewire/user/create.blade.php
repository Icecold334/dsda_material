<x-modal name="create-user" focusable>
    <form wire:submit="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            Tambah Pengguna Baru
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name" value="Nama" />
                <x-text-input id="name" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" wire:model="email" type="email" class="mt-1 block w-full"
                    placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" wire:model="password" type="password" class="mt-1 block w-full"
                    placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                <x-text-input id="password_confirmation" wire:model="password_confirmation" type="password"
                    class="mt-1 block w-full" placeholder="Ulangi password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="nip" value="NIP" />
                <x-text-input id="nip" wire:model="nip" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan NIP (opsional)" />
                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id" value="Sudin" />
                <livewire:components.select-input wire:model="sudin_id" :options="$sudins->pluck('name', 'id')"
                    placeholder="-- Pilih Sudin --" :key="'sudin-select'" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="division_id" value="Divisi" />
                <livewire:components.select-input wire:model="division_id" :options="$divisions->pluck('name', 'id')"
                    placeholder="-- Pilih Divisi --" :key="'division-select'" />
                <x-input-error :messages="$errors->get('division_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="position_id" value="Jabatan" />
                <livewire:components.select-input wire:model="position_id" :options="$positions->pluck('name', 'id')"
                    placeholder="-- Pilih Jabatan --" :key="'position-select'" />
                <x-input-error :messages="$errors->get('position_id')" class="mt-2" />
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
                <x-input-error :messages="$errors->get('ttd')" class="mt-2" />
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