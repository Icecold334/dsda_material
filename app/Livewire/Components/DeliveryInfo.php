<?php

namespace App\Livewire\Components;

use App\Models\RequestModel;
use App\Models\Personnel;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeliveryInfo extends Component
{
    public RequestModel $permintaan;

    // Delivery Information
    public $nopol;
    public $driver_id;
    public $security_id;
    public $ttd_driver;
    public $ttd_security;
    public $canEditDelivery = true;

    public function mount()
    {
        // Check if delivery information is already filled
        // If filled, switch to show mode (not editable)
        $this->canEditDelivery = empty($this->permintaan->nopol) &&
            empty($this->permintaan->driver_id) &&
            empty($this->permintaan->security_id);

        // Load existing data
        $this->nopol = $this->permintaan->nopol;
        $this->driver_id = $this->permintaan->driver_id;
        $this->security_id = $this->permintaan->security_id;
    }

    public function saveDeliveryInfo()
    {
        $this->validate([
            'nopol' => 'required|string|max:20',
            'driver_id' => 'required|exists:personnels,id',
            'security_id' => 'required|exists:personnels,id',
            'ttd_driver' => 'required|string',
            'ttd_security' => 'required|string',
        ], [
            'nopol.required' => 'Nomor polisi harus diisi',
            'driver_id.required' => 'Driver harus dipilih',
            'security_id.required' => 'Security harus dipilih',
            'ttd_driver.required' => 'Tanda tangan driver harus diisi',
            'ttd_security.required' => 'Tanda tangan security harus diisi',
        ]);

        // Update permintaan
        $this->permintaan->update([
            'nopol' => $this->nopol,
            'driver_id' => $this->driver_id,
            'security_id' => $this->security_id,
        ]);

        // Save driver signature
        if ($this->ttd_driver) {
            $this->saveSignature($this->ttd_driver, 'ttd_driver');
        }

        // Save security signature
        if ($this->ttd_security) {
            $this->saveSignature($this->ttd_security, 'ttd_security');
        }

        // Switch to show mode after saving
        $this->canEditDelivery = false;

        session()->flash('message', 'Informasi pengiriman berhasil disimpan');

        // Dispatch event to notify parent component
        $this->dispatch('delivery-info-saved');
        $this->dispatch('approvalExtraCheckRequested');

    }

    private function saveSignature($signatureData, $category)
    {
        // Delete existing signature for this category
        $this->permintaan->documents()
            ->where('category', $category)
            ->delete();

        // Decode base64 image
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        // Generate filename
        $filename = $category . '_' . $this->permintaan->id . '_' . time() . '.png';
        $path = 'signatures/' . $filename;

        // Store file
        Storage::disk('public')->put($path, $imageData);

        // Create document record
        Document::create([
            'documentable_type' => RequestModel::class,
            'documentable_id' => $this->permintaan->id,
            'category' => $category,
            'file_path' => $path,
            'file_name' => $filename,
            'mime_type' => 'image/png',
            'size_kb' => strlen($imageData) / 1024,
            'user_id' => Auth::id(),
        ]);
        $this->dispatch('approvalExtraCheckRequested');

    }

    public function getDriversProperty()
    {
        return Personnel::where('sudin_id', $this->permintaan->sudin_id)
            ->where('type', 'driver')
            ->get();
    }

    public function getSecuritiesProperty()
    {
        return Personnel::where('sudin_id', $this->permintaan->sudin_id)
            ->where('type', 'security')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.delivery-info', [
            'drivers' => $this->drivers,
            'securities' => $this->securities,
        ]);
    }
}
