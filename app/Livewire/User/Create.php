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

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $sudin_id = '';
    public $division_id = '';
    public $position_id = '';
    public $ttd = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
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
                'password.required' => 'Password wajib diisi',
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

        $this->dispatch('validation-passed-create');
        return;
    }

    #[On('confirm-save-user')]
    public function confirmSave()
    {
        $ttdPath = null;

        // Simpan tanda tangan jika ada
        if ($this->ttd) {
            // Decode base64 image
            $image = str_replace('data:image/png;base64,', '', $this->ttd);
            $image = str_replace(' ', '+', $image);
            $imageName = 'ttd_' . time() . '_' . uniqid() . '.png';

            // Simpan ke folder public/ttd
            Storage::disk('public')->put('ttd/' . $imageName, base64_decode($image));
            $ttdPath = 'ttd/' . $imageName;
        }

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'sudin_id' => $this->sudin_id ?: null,
            'division_id' => $this->division_id ?: null,
            'position_id' => $this->position_id ?: null,
            'ttd' => $ttdPath,
        ]);

        $this->dispatch('close-modal', 'create-user');
        $this->dispatch('success-created', message: 'Pengguna berhasil ditambahkan');
        $this->dispatch('user-created');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.user.create', [
            'sudins' => Sudin::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }
}