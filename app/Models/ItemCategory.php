<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ItemCategory extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
