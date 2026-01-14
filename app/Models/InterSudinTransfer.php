<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InterSudinTransfer extends Model
{
    use HasUuids, SoftDeletes, HasStatus;

    protected $guarded = [];

    protected $casts = [
        'tanggal_transfer' => 'datetime',
    ];

    public function sudinPengirim()
    {
        return $this->belongsTo(Sudin::class, 'sudin_pengirim_id');
    }

    public function sudinPenerima()
    {
        return $this->belongsTo(Sudin::class, 'sudin_penerima_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InterSudinTransferItem::class, 'inter_sudin_transfer_id');
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
