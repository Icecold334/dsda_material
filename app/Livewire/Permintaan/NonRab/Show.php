<?php

namespace App\Livewire\Permintaan\NonRab;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\RequestModel;
use Livewire\Attributes\Title;
use App\Services\ApprovalService;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    protected $listeners = [
        'approvalExtraCheckRequested' => 'handleExtraCheck',
        'approvalRejected' => 'onApprovalRejected',
        'confirmSubmit' => 'sendRequest',
        'confirmDelete' => 'deleteRequest',
        'saveDocuments' => 'saveDocuments',
        'documentsSaved' => 'onDocumentsSaved',
        'documentSaveError' => 'onDocumentSaveError'
    ];

    public function mount()
    {
        $this->dispatch('setRequestDataById', [
            'permintaan_id' => $this->permintaan->id,
            'is_rab' => false
        ]);
    }

    public function handleExtraCheck()
    {
        $ready = true;
        $message = '';

        $currentApproval = $this->permintaan->approvals()
            ->where('status', 'pending')
            ->orderBy('level')
            ->first();

        if (!$currentApproval) {
            $this->dispatch('approvalExtraCheckResult', ready: $ready, message: $message);
            return;
        }

        $positionSlug = $currentApproval->position->slug;

        // Jika bukan pengurus barang, langsung lanjut
        if ($positionSlug !== 'pengurus-barang') {
            $this->dispatch('approvalExtraCheckResult', ready: $ready, message: $message);
            return;
        }

        // Jika pengurus barang, pastikan status approved
        if ($this->permintaan->status !== 'approved') {
            $this->permintaan->status = 'approved';
            $this->permintaan->save();
        }

        // Validasi tambahan
        if (!$this->hasPickupPhotos() && false) {
            $ready = false;
            $message = 'Foto barang belum lengkap';
        } elseif (!$this->permintaan->driver_id) {
            $ready = false;
            $message = 'Driver belum dipilih';
        } elseif (!$this->permintaan->security_id) {
            $ready = false;
            $message = 'Security belum dipilih';
        }

        $this->dispatch('approvalExtraCheckResult', ready: $ready, message: $message);
    }

    public function onApprovalRejected()
    {
        // $this->permintaan->status = 'rejected';
        // $this->permintaan->save();

        // optional:
        // kirim notifikasi ke pemohon
    }

    public function sendRequest()
    {
        app(ApprovalService::class)->init($this->permintaan, 'permintaanNonRab');

        $this->permintaan->status = 'pending';
        $this->permintaan->save();

    }

    public function deleteRequest()
    {
        $nomor = $this->permintaan->nomor;
        $this->permintaan->forceDelete();
        return $this->dispatch('deleteSuccess', nomor: $nomor);
    }

    public function saveDocuments($modelId)
    {
        // This method will be called by DocumentUpload component
        // The actual saving is handled by DocumentUpload's saveDocuments method
        // We can add any additional logic here if needed, like refreshing data
        $this->dispatch('documentsSaved');
    }

    public function onDocumentsSaved()
    {
        // Show success message
        $this->dispatch('showAlert', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Dokumen berhasil diupload',
        ]);
    }

    public function onDocumentSaveError($message)
    {
        // Show error message
        $this->dispatch('showAlert', [
            'type' => 'error',
            'title' => 'Gagal!',
            'text' => 'Gagal upload dokumen: ' . $message,
        ]);
    }

    protected function hasPickupPhotos(): bool
    {
        // cek documents category pickup_photo misalnya
        return Document::where('documentable_type', RequestModel::class)
            ->where('documentable_id', $this->permintaan->id)
            ->where('category', 'pickup_photo')
            ->exists();
    }

    public function render()
    {
        return view('livewire.permintaan.non-rab.show');
    }
}