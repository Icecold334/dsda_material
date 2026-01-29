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
        return $this->hasMany(RabAmendment::class)->orderBy('amend_version', 'desc');
    }

    // Get latest version (amendment or original RAB)
    public function getLatestVersionAttribute()
    {
        $latestAmendment = $this->amendments()->where('status', 'approved')->first();
        return $latestAmendment ?? $this;
    }

    // Check if RAB has approved amendments
    public function hasApprovedAmendments()
    {
        return $this->amendments()->where('status', 'approved')->exists();
    }

    // Get next amendment version number
    public function getNextAmendmentVersion()
    {
        $latestVersion = $this->amendments()->max('amend_version') ?? 0;
        return $latestVersion + 1;
    }

    // Get active items (from latest amendment or original)
    public function getActiveItemsAttribute()
    {
        if ($this->hasApprovedAmendments()) {
            return $this->latestVersion->items;
        }
        return $this->items;
    }

    // Get total requested quantity for an item
    public function getRequestedQuantity($itemId)
    {
        return \App\Models\RequestItem::whereHas('request', function ($q) {
            $q->where('rab_id', $this->id)
                ->whereNotIn('status', ['draft', 'rejected']); // Hanya hitung permintaan yang sudah diajukan
        })->where('item_id', $itemId)->sum('qty_request');
    }
}
