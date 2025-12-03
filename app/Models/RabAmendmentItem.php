<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RabAmendmentItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function rabAmendment()
    {
        return $this->belongsTo(RabAmendment::class, 'rab_amendment_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
