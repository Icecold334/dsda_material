<?php

namespace App\Livewire\Permintaan\NonRab;

use Livewire\Component;
use App\Models\RequestModel;
use Livewire\Attributes\Title;
use App\Services\ApprovalService;
use Livewire\Attributes\On;

class Show extends Component
{

    #[Title('Detail Permintaan')]
    public RequestModel $permintaan;

    protected $listeners = [
        'approvalExtraCheckRequested' => 'handleExtraCheck',
        'approvalRejected' => 'onApprovalRejected',
    ];

    public function mount()
    {
        // $this->dispatch('approvalExtraCheckResult', ready: false, message: 'atewaiuy');
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
        if ($current->position->slug !== 'pengurus-barang') {
            $this->dispatch(
                'approvalExtraCheckResult',
                ready: $ready,
                message: $message
            );
            return;
        }




    }
    public function onApprovalRejected()
    {
        $this->permintaan->status = 'rejected';
        $this->permintaan->save();

        // optional:
        // kirim notifikasi ke pemohon
    }

    public function sendRequest()
    {
        app(ApprovalService::class)->init($this->permintaan, 'permintaanNonRab');

        $this->permintaan->status = 'pending';
        $this->permintaan->save();

    }

    public function render()
    {
        return view('livewire.permintaan.non-rab.show');
    }
}
