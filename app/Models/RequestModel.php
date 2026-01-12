<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RequestModel extends Model
{
    use HasUuids, SoftDeletes, HasStatus;

    protected $table = 'requests';   // penting!
    protected $guarded = [];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    // Relations
    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Personnel::class, 'driver_id');
    }

    public function security()
    {
        return $this->belongsTo(Personnel::class, 'security_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function items()
    {
        return $this->hasMany(RequestItem::class, 'request_id');
    }

    public function approvals()
    {
        return $this->morphMany(RequestApproval::class, 'document');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
