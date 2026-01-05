<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sudin extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    // Relations
    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function personnels()
    {
        return $this->hasMany(Personnel::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function securities()
    {
        return $this->hasMany(Security::class);
    }
}
