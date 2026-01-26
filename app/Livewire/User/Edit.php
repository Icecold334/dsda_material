<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public User $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $sudin_id = '';
    public $division_id = '';
    public $position_id = '';
    public $ttd = '';
    public $existing_ttd = '';

    public function mount()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->sudin_id = $this->user->sudin_id;
        $this->division_id = $this->user->division_id;
        $this->position_id = $this->user->position_id;
        $this->existing_ttd = $this->user->ttd;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'sudin_id' => 'nullable|exists:sudins,id',
            'division_id' => 'nullable|exists:divisions,id',
            'position_id' => 'nullable|exists:positions,id',
            'ttd' => 'nullable|string',
        ];
    }

    public function validateForm()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'sudin_id' => $this->sudin_id,
                'division_id' => $this->division_id,
                'position_id' => $this->position_id,
                'ttd' => $this->ttd,
            ],
            $this->rules(),
            [
                'name.required' => 'Nama pengguna wajib diisi',
                'name.string' => 'Nama pengguna harus berupa teks',
                'name.max' => 'Nama pengguna maksimal 255 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.string' => 'Password harus berupa teks',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'sudin_id.exists' => 'Sudin yang dipilih tidak valid',
                'division_id.exists' => 'Divisi yang dipilih tidak valid',
                'position_id.exists' => 'Jabatan yang dipilih tidak valid',
                'ttd.string' => 'Tanda tangan harus berupa teks',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('alert', type: 'error', title: 'Gagal!', text: $validator->errors()->first());
            return;
        }

        $this->dispatch('validation-passed-update');
        return;
    }

    #[On('confirm-update-user')]
    public function confirmUpdate()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'sudin_id' => $this->sudin_id ?: null,
            'division_id' => $this->division_id ?: null,
            'position_id' => $this->position_id ?: null,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        // Simpan tanda tangan baru jika ada
        if ($this->ttd) {
            // Hapus tanda tangan lama jika ada
            if ($this->user->ttd && Storage::disk('public')->exists($this->user->ttd)) {
                Storage::disk('public')->delete($this->user->ttd);
            }

            // Decode base64 image
            $image = str_replace('data:image/png;base64,', '', $this->ttd);
            $image = str_replace(' ', '+', $image);
            $imageName = 'ttd_' . time() . '_' . uniqid() . '.png';

            // Simpan ke folder public/ttd
            Storage::disk('public')->put('ttd/' . $imageName, base64_decode($image));
            $data['ttd'] = 'ttd/' . $imageName;
        }

        $this->user->update($data);

        $this->dispatch('close-modal', 'edit-user-' . $this->user->id);
        $this->dispatch('success-updated', message: 'Pengguna berhasil diperbarui');
        $this->dispatch('user-updated');
    }

    public function render()
    {
        return view('livewire.user.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
            'existing_ttd' => $this->existing_ttd,
        ]);
    }
}