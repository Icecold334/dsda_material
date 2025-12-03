<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DeliveryItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
