<?php

namespace App\Models\Concerns;

trait HasStatus
{
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'info',
            'pending' => 'warning',
            'approved' => 'success',
            'expired' => 'secondary',
            'rejected' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draf',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'expired' => 'Kadaluarsa',
            'rejected' => 'Ditolak',
            default => 'Draf',
        };
    }
}
