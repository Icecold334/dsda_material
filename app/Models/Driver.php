<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasUuids, SoftDeletes;
    protected $guarded = [];

    public function sudin(): BelongsTo
    {
        return $this->belongsTo(Sudin::class);
    }
}
