<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ItemUnit extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected static function booted()
    {
        static::creating(function ($unit) {
            $base = Str::slug($unit->name);

            // $count = static::where('slug', 'like', "{$base}%")
            //     ->count();

            $unit->slug = $base;
        });
    }
    public function categories()
    {
        return $this->hasMany(ItemCategory::class);
    }
}