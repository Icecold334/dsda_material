<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Division extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
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

    // Scope untuk filter hanya kecamatan
    public function scopeDistricts($query)
    {
        return $query->where('type', 'district');
    }
}
