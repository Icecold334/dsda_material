<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RequestItem extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    public function request()
    {
        return $this->belongsTo(RequestModel::class, 'request_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function photo()
    {
        return $this->morphOne(Document::class, 'documentable')->where('category', 'item_photo');
    }
}
