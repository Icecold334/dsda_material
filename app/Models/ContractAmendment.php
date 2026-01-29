<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ContractAmendment extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function items()
    {
        return $this->hasMany(ContractAmendmentItem::class, 'contract_amendment_id');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Draft',
        };
    }

    // Check if item quantity can be reduced
    public function canReduceItemQuantity($itemId, $newQuantity)
    {
        $deliveredQty = $this->contract->getDeliveredQuantity($itemId);
        return $newQuantity >= $deliveredQty;
    }
}
