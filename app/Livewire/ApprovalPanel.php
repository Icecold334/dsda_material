<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\RequestApproval;
use App\Services\ApprovalService;
use Illuminate\Database\Eloquent\Model;

class ApprovalPanel extends Component
{
    public string $module;
    public $approvals;
    public $isComplete = false;
    public string $approvableType;
    public string|int $approvableId;
    public bool $showRejectForm = false;
    public bool $extraReady = false; // default: level ini cuma klik approve
    public string $extraError = '';

    protected $listeners = [
        // parent akan balas dengan flag siap / tidak
        'approvalExtraCheckResult' => 'onExtraCheckResult',
        'confirmApprove' => 'approve',
        'confirmReject' => 'reject'
    ];

    public function mount(string $module, string $approvableType, $approvableId, ApprovalService $approvalService)
    {
        $this->module = $module;
        $this->approvableType = $approvableType;
        $this->approvableId = $approvableId;

        $this->dispatch('approvalExtraCheckRequested');
        $this->getApprovals($approvalService);

    }


    public function onExtraCheckResult(bool $ready, string $message = '', ApprovalService $approvalService)
    {
        $this->extraReady = $ready;
        $this->extraError = $message;
        $this->getApprovals($approvalService);
    }

    protected function getModel(): Model
    {
        $class = $this->approvableType;
        return $class::findOrFail($this->approvableId);
    }
    protected function getApprovals(ApprovalService $approvalService)
    {
        $this->approvals = RequestApproval::with(['position', 'division'])
            ->where('document_type', $this->approvableType)
            ->where('document_id', $this->approvableId)
            ->orderBy('level')
            ->get()
            ->map(function ($approval, $i) use ($approvalService) {
                // dd($approval);
                $approval->approver_user = $approvalService->getApproverUserFor($approval, $i);
                return $approval;
            });
    }


    public function getButtonLabelProperty(): string
    {
        $current = $this->approvals->firstWhere('status', 'pending');

        return $current
            ? 'Menunggu ' . $current->position->name . ' ' . $current->division?->name
            : 'Sudah Disetujui';
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
        if ($approvalService->isComplete($model)) {
            $this->isComplete = true;
            $this->dispatch('approvalComplete');
        }
        $this->getApprovals($approvalService);

    }

    public function reject($rejectReason, ApprovalService $approvalService)
    {

        $model = $this->getModel();
        $user = auth()->user();

        if (!$user) {
            $this->addError('reject', 'Silakan login');
            return;
        }

        if (trim($rejectReason) === '') {
            $this->addError('reject', 'Alasan penolakan wajib diisi');
            return;
        }

        $approvalService->reject($model, $user, $rejectReason);

        $this->dispatch('approvalRejected');
        $this->getApprovals($approvalService);

    }




    public function render(ApprovalService $approvalService)
    {
        $model = $this->getModel();

        $user = auth()->user();
        return view('livewire.approval-panel', [
            'canApprove' => $user ? $approvalService->canApprove($model, $user) : false,
            'isFinal' => $approvalService->isFinal($model),
            'isGuest' => !$user,
            'currentApprover' => $approvalService->getCurrentApproverUser($model),
        ]);

    }
}
