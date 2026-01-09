<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Item extends Model
{
    use HasUuids, SoftDeletes;

    protected static function booted()
    {
        static::creating(function ($item) {
            $base = Str::slug(
                $item->spec
            );

            $count = static::where('sudin_id', $item->sudin_id)
                ->where('slug', 'like', "{$base}%")
                ->count();

            $item->slug = $count
                ? "{$base}-" . ($count + 1)
                : $base;
        });
    }

    protected $guarded = [];

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
