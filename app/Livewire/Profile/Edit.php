<?php

namespace App\Livewire\Profile;

use App\Models\Sudin;
use App\Models\Division;
use App\Models\Position;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $name = '';
    public $email = '';
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $sudin_id = '';
    public $division_id = '';
    public $position_id = '';
    public $ttd = '';
    public $existing_ttd = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->sudin_id = $user->sudin_id;
        $this->division_id = $user->division_id;
        $this->position_id = $user->position_id;
        $this->existing_ttd = $user->ttd;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
            'sudin_id' => 'nullable|exists:sudins,id',
            'division_id' => 'nullable|exists:divisions,id',
            'position_id' => 'nullable|exists:positions,id',
            'ttd' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required_with' => 'Password saat ini harus diisi jika ingin mengubah password.',
        ];
    }

    public function update()
    {
        $this->validate();

        $user = auth()->user();

        // Validasi password saat ini jika ingin mengubah password
        if ($this->password && !Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

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
            if ($user->ttd && Storage::disk('public')->exists($user->ttd)) {
                Storage::disk('public')->delete($user->ttd);
            }

            // Decode base64 image
            $image = str_replace('data:image/png;base64,', '', $this->ttd);
            $image = str_replace(' ', '+', $image);
            $imageName = 'ttd_' . time() . '_' . uniqid() . '.png';

            // Simpan ke folder public/ttd
            Storage::disk('public')->put('ttd/' . $imageName, base64_decode($image));
            $data['ttd'] = 'ttd/' . $imageName;
        }

        $user->update($data);

        session()->flash('message', 'Profil berhasil diperbarui.');

        $this->dispatch('close-modal', 'edit-profile');
        $this->dispatch('profile-updated');

        // Reset password fields
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->ttd = '';
    }

    public function render()
    {
        return view('livewire.profile.edit', [
            'sudins' => Sudin::orderBy('name')->get(),
            'divisions' => Division::orderBy('name')->get(),
            'positions' => Position::orderBy('name')->get(),
        ]);
    }
}
