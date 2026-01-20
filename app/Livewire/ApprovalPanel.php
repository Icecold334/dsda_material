<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ApprovalService;
use Illuminate\Database\Eloquent\Model;

class ApprovalPanel extends Component
{
    public string $module;
    public string $approvableType;
    public string|int $approvableId;

    public bool $extraReady = true; // default: level ini cuma klik approve
    public string $extraError = '';

    protected $listeners = [
        // parent akan balas dengan flag siap / tidak
        'approvalExtraCheckResult' => 'onExtraCheckResult',
    ];

    public function mount(string $module, string $approvableType, $approvableId)
    {
        $this->module = $module;
        $this->approvableType = $approvableType;
        $this->approvableId = $approvableId;

        // trigger check syarat tambahan di parent (kalau parent mau)
        $this->dispatch('approvalExtraCheckRequested');
    }

    public function onExtraCheckResult(bool $ready, string $message = '')
    {
        $this->extraReady = $ready;
        $this->extraError = $message;
    }

    protected function getModel(): Model
    {
        $class = $this->approvableType;
        return $class::findOrFail($this->approvableId);
    }

    public function approve(ApprovalService $approvalService)
    {
        $model = $this->getModel();

        // 1) cek syarat tambahan dari modul (upload/e-sign/driver)
        if (!$this->extraReady) {
            $this->addError('approve', $this->extraError ?: 'Syarat belum lengkap');
            return;
        }

        // 2) approve global (jabatan + PLT)
        $approvalService->approve($model, auth()->user());

        // 3) refresh: minta parent cek lagi (kadang level berubah)
        $this->dispatch('approvalExtraCheckRequested');
        $this->dispatch('approvalApproved'); // parent bisa nangkap buat update status bisnis dll
        session()->flash('success', 'Approval berhasil');
    }

    public function render(ApprovalService $approvalService)
    {
        $model = $this->getModel();

        $user = auth()->user();

        return view('livewire.approval-panel', [
            'canApprove' => $user ? $approvalService->canApprove($model, $user) : false,
            'isFinal' => $approvalService->isFinal($model),
            'isGuest' => !$user,
        ]);

    }
}
