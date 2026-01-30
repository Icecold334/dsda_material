<?php

namespace App\Models\Concerns;

trait HasStatus
{
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'success',
            'shipping' => 'info',
            'completed' => 'primary',
            'expired' => 'secondary',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Diproses',
            'approved' => 'Disetujui',
            'shipping' => 'Dikirim',
            'completed' => 'Selesai',
            'expired' => 'Kadaluarsa',
            'rejected' => 'Ditolak',
            default => 'Draft',
        };
    }
}
