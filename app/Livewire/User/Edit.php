<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function update()
    {
        $this->validate();

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