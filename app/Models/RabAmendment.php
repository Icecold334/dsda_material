<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RabAmendment extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function rab()
    {
        return $this->belongsTo(Rab::class);
    }

    public function items()
    {
        return $this->hasMany(RabAmendmentItem::class);
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
        $requestedQty = $this->rab->getRequestedQuantity($itemId);
        return $newQuantity >= $requestedQty;
    }
}
