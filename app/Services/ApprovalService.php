<?php

namespace App\Services;

use App\Models\User;
use App\Models\ApprovalFlow;
use App\Models\RequestApproval;
use App\Models\PositionDelegation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ApprovalService
{
    /**
     * Inisialisasi approval saat dokumen dibuat
     */
    public function init(Model $model, string $module): void
    {
        $flows = ApprovalFlow::where('module', $module)
            ->where('sudin_id', $model->sudin_id)
            ->orderBy('level')
            ->get();

        DB::transaction(function () use ($flows, $model) {
            foreach ($flows as $flow) {
                RequestApproval::create([
                    'document_type' => get_class($model),
                    'document_id' => $model->id,
                    'level' => $flow->level,
                    'position_id' => $flow->position_id,
                    'division_id' => $flow->division_id,
                    'sudin_id' => Auth::user()->sudin->id,
                    'status' => 'pending',
                ]);
            }
        });
    }
    public function isRejected(Model $model): bool
    {
        return RequestApproval::where('document_type', get_class($model))
            ->where('document_id', $model->id)
            ->where('status', 'rejected')
            ->exists();
    }

    /**
     * Cek apakah user boleh approve level aktif
     */
    public function canApprove(Model $model, User $user): bool
    {
        if ($this->isRejected($model)) {
            return false;
        }
        // dd($model);
        $current = $this->getCurrentApproval($model);
        if (!$current) {
            return false;
        }

        // 1. cek jabatan asli
        if (
            $user->position_id === $current->position_id &&
            (
                is_null($current->division_id) ||
                $user->division_id === $current->division_id
            )
        ) {
            return true;
        }

        // 2. cek PLT
        return PositionDelegation::where('user_id', $user->id)
            ->where('position_id', $current->position_id)
            ->where(function ($q) use ($current) {
                $q->whereNull('division_id')
                    ->orWhere('division_id', $current->division_id);
            })
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
    }

    /**
     * Proses approve level aktif
     */
    public function approve(Model $model, User $user): void
    {
        $current = $this->getCurrentApproval($model);
        if (!$current) {
            throw new \Exception('Tidak ada approval aktif');
        }

        if (!$this->canApprove($model, $user)) {
            throw new \Exception('User tidak berwenang approve');
        }

        DB::transaction(function () use ($current, $user) {
            $current->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
        });
    }

    public function reject(Model $model, User $user, string $reason): void
    {
        $current = $this->getCurrentApproval($model);

        if (!$current) {
            throw new \Exception('Tidak ada approval aktif');
        }

        if (!$this->canApprove($model, $user)) {
            throw new \Exception('User tidak berwenang menolak');
        }

        DB::transaction(function () use ($current, $user, $reason) {
            $current->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'notes' => $reason,
            ]);
        });
    }


    /**
     * Apakah approval sudah final
     */
    public function isFinal(Model $model): bool
    {
        return !RequestApproval::where('document_type', get_class($model))
            ->where('document_id', $model->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Ambil approval yang sedang aktif
     */
    protected function getCurrentApproval(Model $model): ?RequestApproval
    {
        return RequestApproval::where('document_type', get_class($model))
            ->where('document_id', $model->id)
            ->where('status', 'pending')
            ->orderBy('level')
            ->first();
    }

    public function getCurrentApproverUser(Model $model): ?User
    {
        if ($this->isRejected($model)) {
            return null;
        }

        $current = $this->getCurrentApproval($model);
        if (!$current) {
            return null;
        }

        // 1️⃣ Kalau user login adalah approver → return dia
        $authUser = auth()->user();
        if ($authUser && $this->canApprove($model, $authUser)) {
            return $authUser;
        }

        // 2️⃣ Cari PLT aktif
        $pltUserId = PositionDelegation::where('position_id', $current->position_id)
            ->where(function ($q) use ($current) {
                $q->whereNull('division_id')
                    ->orWhere('division_id', $current->division_id);
            })
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->value('user_id');

        if ($pltUserId) {
            return User::find($pltUserId);
        }

        // 3️⃣ User jabatan asli
        return User::where('position_id', $current->position_id)
            ->where('sudin_id', $model->sudin_id)
            ->when(
                $current->division_id,
                fn($q) =>
                $q->where('division_id', $current->division_id)
            )
            ->orderBy('id')
            ->first();
    }

    public function getApproverUserFor(RequestApproval $approval, $i = 0)
    {



        // 1️⃣ Cari PLT aktif (diutamakan)
        $pltUserId = PositionDelegation::where('position_id', $approval->position_id)
            ->where(function ($q) use ($approval) {
                $q->whereNull('division_id')
                    ->orWhere('division_id', $approval->division_id);
            })
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->value('user_id');

        if ($pltUserId) {
            return User::find($pltUserId);
        }
        // 2️⃣ User jabatan asli
        $user = User::where('position_id', $approval->position_id)
            ->where('sudin_id', $approval->sudin_id)
            ->when(
                $approval->division_id,
                fn($q) => $q->where('division_id', $approval->division_id)
            )
            ->orderBy('id')
            ->firstorFail();

        return $user;
    }

}
