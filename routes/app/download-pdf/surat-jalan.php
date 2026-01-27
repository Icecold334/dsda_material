<?php

use App\Models\RequestModel;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPdf\Facades\Pdf;

Route::prefix('download-pdf')->name('download-pdf.')->group(function () {

    // Surat Jalan untuk Permintaan Non RAB
    Route::get('/surat-jalan/non-rab/{permintaan}', function (RequestModel $permintaan) {
        $permintaan->load([
            'user',
            'district',
            'subdistrict',
            'items.item.category.unit',
            'driver',
            'security',
            'sudin',
            'documents'
        ]);

        return Pdf::view('pdf.surat-jalan', [
            'permintaan' => $permintaan
        ])
            ->format('a4')
            ->name('surat-jalan-' . $permintaan->nomor . '.pdf');
    })->name('surat-jalan.non-rab');

    // Surat Jalan untuk Permintaan RAB
    Route::get('/surat-jalan/rab/{permintaan}', function (RequestModel $permintaan) {
        $permintaan->load([
            'user',
            'district',
            'subdistrict',
            'items.item.category.unit',
            'driver',
            'security',
            'sudin',
            'documents'
        ]);

        return Pdf::view('pdf.surat-jalan', [
            'permintaan' => $permintaan
        ])
            ->format('a4')
            ->name('surat-jalan-' . $permintaan->nomor . '.pdf');
    })->name('surat-jalan.rab');
});
