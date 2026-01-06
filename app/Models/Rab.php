<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Rab extends Model
{
    use HasUuids, SoftDeletes, HasStatus;

    protected $guarded = [];



    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    // public function aktivitasSubKegiatan()
    // {
    //     return $this->belongsTo(AktivitasSubKegiatan::class, 'aktivitas_sub_kegiatan_id');
    // }

    public function items()
    {
        return $this->hasMany(RabItem::class);
    }

    public function amendments()
    {
        return $this->hasMany(RabAmendment::class);
    }
}
