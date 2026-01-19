<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ItemCategory extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($category) {
            $base = Str::slug($category->name);

            // $count = static::where('sudin_id', $category->sudin_id)
            //     ->where('slug', 'like', "{$base}%")
            //     ->count();

            $category->slug = $base;
        });
    }


    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'item_unit_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }
}
