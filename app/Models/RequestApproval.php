<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RequestApproval extends Model
{
    use HasUuids, SoftDeletes, HasStatus;

    protected $guarded = [];

    protected function casts()
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    // polymorphic to any document
    public function document()
    {
        return $this->morphTo();
    }

    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
