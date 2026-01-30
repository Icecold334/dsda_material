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
        return $this->hasMany(ContractAmendment::class)->orderBy('amend_version', 'desc');
    }

    // Get latest version (amendment or original contract)
    public function getLatestVersionAttribute()
    {
        $latestAmendment = $this->amendments()->where('status', 'approved')->first();
        return $latestAmendment ?? $this;
    }

    // Check if contract has approved amendments
    public function hasApprovedAmendments()
    {
        return $this->amendments()->where('status', 'approved')->exists();
    }

    // Get next amendment version number
    public function getNextAmendmentVersion()
    {
        $latestVersion = $this->amendments()->max('amend_version') ?? 0;
        return $latestVersion + 1;
    }

    // Get active items (from latest amendment or original)
    public function getActiveItemsAttribute()
    {
        if ($this->hasApprovedAmendments()) {
            return $this->latestVersion->items;
        }
        return $this->items;
    }

    // Get total delivered quantity for an item
    public function getDeliveredQuantity($itemId)
    {
        return \App\Models\DeliveryItem::whereHas('delivery', function ($q) {
            $q->where('contract_id', $this->id);
        })->where('item_id', $itemId)->sum('qty');
    }
}
