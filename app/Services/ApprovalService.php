<?php

namespace App\Services;

use App\Models\ApprovalFlow;
use App\Models\RequestApproval;
use App\Models\PositionDelegation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                    'approvable_type' => get_class($model),
                    'approvable_id' => $model->id,
                    'level' => $flow->level,
                    'position_id' => $flow->position_id,
                    'division_id' => $flow->division_id,
                    'status' => 'pending',
                ]);
            }
        });
    }

    /**
     * Cek apakah user boleh approve level aktif
     */
    public function canApprove(Model $model, User $user): bool
    {
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
                'approved_by_user_id' => $user->id,
                'approved_at' => now(),
            ]);
        });
    }

    /**
     * Apakah approval sudah final
     */
    public function isFinal(Model $model): bool
    {
        return !RequestApproval::where('approvable_type', get_class($model))
            ->where('approvable_id', $model->id)
            ->where('status', 'pending')
            ->exists();
    }

    /**
     * Ambil approval yang sedang aktif
     */
    protected function getCurrentApproval(Model $model): ?RequestApproval
    {
        return RequestApproval::where('approvable_type', get_class($model))
            ->where('approvable_id', $model->id)
            ->where('status', 'pending')
            ->orderBy('level')
            ->first();
    }
}
