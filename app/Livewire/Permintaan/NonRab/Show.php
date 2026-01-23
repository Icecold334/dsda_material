<?php

namespace App\Livewire\Permintaan\NonRab;

use Livewire\Component;
use App\Models\Document;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\RequestModel;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Services\ApprovalService;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;
  
    protected $listeners = [
        'approvalExtraCheckRequested' => 'handleExtraCheck',
        'approvalRejected' => 'onApprovalRejected',
        'confirmSubmit' => 'sendRequest'
    ];

    public function mount()
    {
        // Dispatch event untuk set data permintaan ke modal
        $this->dispatch('setRequestData', [
            'nomor' => $this->permintaan->nomor,
            'name' => $this->permintaan->name,
            'sudin_id' => $this->permintaan->sudin_id,
            'warehouse_id' => $this->permintaan->warehouse_id,
            'district_id' => $this->permintaan->district_id,
            'subdistrict_id' => $this->permintaan->subdistrict_id,
            'tanggal_permintaan' => $this->permintaan->tanggal_permintaan?->format('Y-m-d'),
            'address' => $this->permintaan->address,
            'panjang' => $this->permintaan->panjang,
            'lebar' => $this->permintaan->lebar,
            'tinggi' => $this->permintaan->tinggi,
            'notes' => $this->permintaan->notes,
        ]);
    }
  
    public function handleExtraCheck()
    {
        $current = $this->permintaan->approvals()
            ->where('status', 'pending')
            ->orderBy('level')
            ->first();
        $ready = true;
        $message = '';
        if (!$this->permintaan->security_id) {
            $ready = false;
            $message = 'Security belum dipilih';
        }
        $positionSlug = $current->position->slug;
        // $divisionSlug = Str::slug($current->division->name);
        if ($positionSlug !== 'pengurus-barang') {
            $this->dispatch(
                'approvalExtraCheckResult',
                ready: $ready,
                message: $message
            );
            return;
        } else if ($positionSlug == 'pengurus-barang') {
            if ($this->permintaan->status !== 'approved') {
                $this->permintaan->status = 'approved';
                $this->permintaan->save();
            }
            if (!$this->hasPickupPhotos()) {
                $ready = false;
                $message = 'Foto barang belum lengkap';
            } elseif (!$this->permintaan->driver_id) {
                $ready = false;
                $message = 'Driver belum dipilih';
            } elseif (!$this->permintaan->security_id) {
                $ready = false;
                $message = 'Security belum dipilih';
            }
        }

        $this->dispatch(
            'approvalExtraCheckResult',
            ready: $ready,
            message: $message
        );
        return;


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
