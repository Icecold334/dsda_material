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

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];


    public function sudin()
    {
        return $this->belongsTo(Sudin::class);
    }

    public function district()
    {
        // Kecamatan sekarang ada di divisions dengan type='district'
        return $this->belongsTo(Division::class, 'district_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
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
