<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ContractItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function receipts()
    {
        return $this->hasMany(ContractReceipt::class);
    }
}
