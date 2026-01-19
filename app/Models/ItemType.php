<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ItemType extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class);
    }
}
