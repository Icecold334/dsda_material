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
}
