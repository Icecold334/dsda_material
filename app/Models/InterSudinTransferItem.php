<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InterSudinTransferItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function transfer()
    {
        return $this->belongsTo(InterSudinTransfer::class, 'inter_sudin_transfer_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
