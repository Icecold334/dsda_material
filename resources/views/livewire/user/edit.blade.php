<x-modal name="edit-user-{{ $user->id }}" focusable>
    <form wire:submit.prevent="confirmUpdate" class="p-6" x-data="{
        confirmUpdate() {
            SwalConfirm.delete({
                eventName: 'confirmUpdateUser',
                eventData: { userId: '{{ $user->id }}' },
                title: 'Update Pengguna?',
                text: 'Data pengguna akan diperbarui.',
                confirmText: 'Ya, update!',
                cancelText: 'Batal'
            });
        }
    }">
        <h2 class="text-lg font-medium text-gray-900">
            Edit Pengguna
        </h2>

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="name-{{ $user->id }}" value="Nama" />
                <x-text-input id="name-{{ $user->id }}" wire:model="name" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan nama" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email-{{ $user->id }}" value="Email" />
                <x-text-input id="email-{{ $user->id }}" wire:model="email" type="email" class="mt-1 block w-full"
                    placeholder="email@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password-{{ $user->id }}" value="Password Baru (Kosongkan jika tidak diubah)" />
                <x-text-input id="password-{{ $user->id }}" wire:model="password" type="password"
                    class="mt-1 block w-full" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation-{{ $user->id }}" value="Konfirmasi Password Baru" />
                <x-text-input id="password_confirmation-{{ $user->id }}" wire:model="password_confirmation"
                    type="password" class="mt-1 block w-full" placeholder="Ulangi password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sudin_id-{{ $user->id }}" value="Sudin" />
                <x-select-input id="sudin_id-{{ $user->id }}" wire:model="sudin_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Sudin --" :options="$sudins->pluck('name', 'id')" />
                <x-input-error :messages="$errors->get('sudin_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="division_id-{{ $user->id }}" value="Divisi" />
                <x-select-input id="division_id-{{ $user->id }}" wire:model="division_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Divisi --" :options="$divisions->pluck('name', 'id')" />
                <x-input-error :messages="$errors->get('division_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="position_id-{{ $user->id }}" value="Jabatan" />
                <x-select-input id="position_id-{{ $user->id }}" wire:model="position_id" class="mt-1 block w-full"
                    placeholder="-- Pilih Jabatan --" :options="$positions->pluck('name', 'id')" />
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
                    
                    @if($existing_ttd)
                        this.loadExistingSignature();
                    @endif
                },
                loadExistingSignature() {
                    const img = new Image();
                    img.onload = () => {
                        this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                    };
                    img.src = '{{ $existing_ttd ? asset('storage/' . $existing_ttd) : '' }}';
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
                <x-input-label class="mb-2">
                    Tanda Tangan @if($existing_ttd)<span class="text-gray-500">(Kosongkan jika tidak
                    diubah)</span>@endif
                </x-input-label>
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
            <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'edit-user-{{ $user->id }}')">
                Batal
            </x-secondary-button>
            <x-primary-button type="button" @click="confirmUpdate()">
                Update
            </x-primary-button>
        </div>
    </form>
</x-modal>