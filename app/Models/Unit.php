<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Unit extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class);  // optional mapping
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
