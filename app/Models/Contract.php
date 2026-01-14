<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contract extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function getStatusColorAttribute()
    {
        return $this->is_api ? 'success' : 'warning';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_api ? 'Terdaftar' : 'Tidak terdaftar';

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
