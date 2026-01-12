<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class District extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class);
    }

    public function rabs()
    {
        return $this->hasMany(Rab::class);
    }
}
