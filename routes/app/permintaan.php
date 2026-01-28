<?php

use App\Models\RequestModel;
use Illuminate\Support\Facades\Route;
use App\Livewire\Permintaan\Rab\Show as ShowRab;
use App\Livewire\Permintaan\Rab\Index as IndexRab;
use App\Livewire\Permintaan\Rab\Create as CreateRab;
use App\Livewire\Permintaan\NonRab\Show as ShowNonRab;
use App\Livewire\Permintaan\NonRab\Index as IndexNonRab;
use App\Livewire\Permintaan\NonRab\Create as CreateNonRab;


Route::prefix('permintaan')->name('permintaan.')->group(function () {

    Route::prefix('rab')->name('rab.')->group(function () {

        Route::get('/', IndexRab::class)->name('index');
        Route::get('/create', CreateRab::class)->name('create');

        Route::get('/json', function () {
            $data = RequestModel::whereNotNull('rab_id')->get()->map(fn($r) => [
                'nomor' => $r->nomor,
                'user' => $r->user->name,
                'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                    . $r->status_text .
                    '</span>',
                'action' => '<a href="' . route('permintaan.rab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');

        Route::get('/{permintaan}/json', function (RequestModel $permintaan) {
            $data = $permintaan->load(['items.item.category.unit', 'items.photo'])->items->map(function ($requestItem, $index) {
                $hasPhoto = $requestItem->photo !== null;

                if ($hasPhoto) {
                    $photoUrl = \Illuminate\Support\Facades\Storage::url($requestItem->photo->file_path);
                    $buttonHtml = '<button type="button" class="btn-view-photo inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition" data-photo-url="' . htmlspecialchars($photoUrl, ENT_QUOTES) . '">Lihat Foto</button>';
                } else {
                    $buttonHtml = '<button type="button" class="btn-upload-photo inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition" data-item-id="' . $requestItem->id . '">Upload Foto</button>';
                }

                return [
                    'no' => $index + 1,
                    'kode' => $requestItem->item->code ?? '-',
                    'barang' => $requestItem->item->category->name ?? '-',
                    'spec' => $requestItem->item->spec ?? '-',
                    'qty_request' => number_format($requestItem->qty_request, 2) . ' ' . ($requestItem->item->category->unit->name ?? ''),
                    'qty_approved' => $requestItem->qty_approved
                        ? number_format($requestItem->qty_approved, 2) . ' ' . ($requestItem->item->category->unit->name ?? '')
                        : '-',
                    'foto' => $buttonHtml,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('show.json');

        Route::post('/{permintaan}/item/{item}/upload-photo', function (RequestModel $permintaan, $item) {
            $request = request();
            $request->validate([
                'photo' => 'required|image|max:5120',
            ]);

            $requestItem = \App\Models\RequestItem::findOrFail($item);

            if ($requestItem->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($requestItem->photo->file_path);
                $requestItem->photo->delete();
            }

            $file = $request->file('photo');
            $path = $file->store('item-photos', 'public');

            \App\Models\Document::create([
                'documentable_type' => \App\Models\RequestItem::class,
                'documentable_id' => $requestItem->id,
                'category' => 'item_photo',
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_kb' => $file->getSize() / 1024,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload'
            ]);
        })->name('item.upload-photo');

        Route::get('/{permintaan}', ShowRab::class)->name('show');
    });
    Route::prefix('non-rab')->name('nonRab.')->group(function () {

        Route::get('/', IndexNonRab::class)->name('index');
        Route::get('/create', CreateNonRab::class)->middleware(['inject.sudin', 'plt'])->name('create');
        Route::get('/json', function () {
            $data = RequestModel::whereNull('rab_id')->when(auth()->user()->sudin, function ($request) {
                if (auth()->user()->division?->type == 'district') {
                    return $request->whereUserId(auth()->id());
                }
                return $request->whereSudinId(auth()->user()->sudin_id);
            })->get()->map(fn($r) => [
                    'nomor' => $r->nomor,
                    'user' => $r->user->name,
                    'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                        . $r->status_text .
                        '</span>',
                    'action' => '<a href="' . route('permintaan.nonRab.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('json');

        Route::get('/{permintaan}/json', function (RequestModel $permintaan) {
            $data = $permintaan->load(['items.item.category.unit', 'items.photo'])->items->map(function ($requestItem, $index) {
                // Generate button HTML based on photo existence
                $hasPhoto = $requestItem->photo !== null;

                if ($hasPhoto) {
                    $photo = $requestItem->photo;
                    $photoUrl = \Illuminate\Support\Facades\Storage::url($photo->file_path);
                    $buttonHtml = '<button type="button" class="btn-view-photo inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition" data-photo-url="' . htmlspecialchars($photoUrl, ENT_QUOTES) . '">
                        Lihat Foto
                    </button>';
                } else {
                    $buttonHtml = '<button type="button" class="btn-upload-photo inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition" data-item-id="' . $requestItem->id . '">
                        Upload Foto
                    </button>';
                }

                return [
                    'no' => $index + 1,
                    'kode' => $requestItem->item->code ?? '-',
                    'barang' => $requestItem->item->category->name ?? '-',
                    'spec' => $requestItem->item->spec ?? '-',
                    'qty_request' => number_format($requestItem->qty_request, 2) . ' ' . ($requestItem->item->category->unit->name ?? ''),
                    'qty_approved' => $requestItem->qty_approved
                        ? number_format($requestItem->qty_approved, 2) . ' ' . ($requestItem->item->category->unit->name ?? '')
                        : '-',
                    'foto' => $buttonHtml,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        })->name('show.json');

        Route::post('/{permintaan}/item/{item}/upload-photo', function (RequestModel $permintaan, $item) {
            $request = request();
            $request->validate([
                'photo' => 'required|image|max:5120',
            ]);

            $requestItem = \App\Models\RequestItem::findOrFail($item);

            // Delete old photo if exists
            if ($requestItem->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($requestItem->photo->file_path);
                $requestItem->photo->delete();
            }

            // Upload new photo
            $file = $request->file('photo');
            $path = $file->store('item-photos', 'public');

            \App\Models\Document::create([
                'documentable_type' => \App\Models\RequestItem::class,
                'documentable_id' => $requestItem->id,
                'category' => 'item_photo',
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_kb' => $file->getSize() / 1024,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload'
            ]);
        })->name('item.upload-photo');

        Route::get('/{permintaan}', ShowNonRab::class)->name('show');
    });


});