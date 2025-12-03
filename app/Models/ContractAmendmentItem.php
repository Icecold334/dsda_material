<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ContractAmendmentItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function amendment()
    {
        return $this->belongsTo(ContractAmendment::class, 'contract_amendment_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
