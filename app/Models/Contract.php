<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contract extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

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
            default => 'draf',
        };
    }

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class);
    }

    public function receipts()
    {
        return $this->hasMany(ContractReceipt::class);
    }

    public function amendments()
    {
        return $this->hasMany(ContractAmendment::class);
    }
}
