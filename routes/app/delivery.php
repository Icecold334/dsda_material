<?php


use App\Models\Contract;
use App\Models\ContractAmendment;
use App\Models\Delivery;
use App\Livewire\Delivery\Show;
use App\Livewire\Delivery\Index;
use App\Livewire\Delivery\Create;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('delivery')->name('delivery.')->group(function () {

    Route::get('/', Index::class)->name('index');

    Route::get('/json', function () {
        $data = Delivery::all()->map(fn($r) => [
            'nomor' => $r->nomor,
            'gudang' => $r->warehouse->name,
            'status' => '<span class="bg-' . $r->status_color . '-600 text-' . $r->status_color . '-100 text-xs font-medium px-2.5 py-0.5 rounded-full">'
                . $r->status_text .
                '</span>',
            'action' => '<a href="' . route('delivery.show', $r->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('json');

    Route::get('/{delivery}/json', function (Delivery $delivery) {
        $data = $delivery->items->map(fn($r) => [
            'kode' => $r->item->code,
            'item' => $r->item->category->name . ' | ' . $r->item->spec,
            'qty' => (int) $r->qty . ' ' . $r->item->category->unit->name,
            'action' => '<a href="' . route('delivery.show', $delivery->id) . '" class="bg-primary-600 text-white text-xs font-medium px-1.5 py-0.5 rounded" wire:navigate>Detail</a>',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    })->name('show.json');
    Route::get('/getContract', function (Request $request) {
        $contractNumber = request()->contractNumber;
        $year = request()->year;

        // First, try to find as amendment number
        $amendment = ContractAmendment::where('nomor', $contractNumber)
            ->where('status', 'approved')
            ->first();

        if ($amendment) {
            // Check if this is the latest amendment
            $latestAmendment = $amendment->contract->amendments()
                ->where('status', 'approved')
                ->first(); // already ordered by amend_version desc

            if ($latestAmendment->id !== $amendment->id) {
                return response()->json([
                    'status' => 'error',
                    'data' => 'Adendum ini sudah tidak berlaku. Gunakan adendum terbaru: ' . $latestAmendment->nomor
                ]);
            }

            // Return the amendment's contract with amendment info
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $amendment->contract_id,
                    'is_amendment' => true,
                    'amendment_id' => $amendment->id,
                    'is_api' => $amendment->contract->is_api ?? false,
                ]
            ]);
        }

        // If not found as amendment, search as original contract
        $contract = Contract::where('nomor', $contractNumber)->first();

        if (!$contract) {
            return response()->json(['status' => 'error', 'data' => 'Kontrak tidak ditemukan!']);
        }

        // Check if this contract has approved amendments
        if ($contract->hasApprovedAmendments()) {
            $latestAmendment = $contract->amendments()->where('status', 'approved')->first();
            return response()->json([
                'status' => 'error',
                'data' => 'Kontrak ini sudah memiliki adendum. Gunakan nomor adendum terbaru: ' . $latestAmendment->nomor
            ]);
        }

        return response()->json(['status' => 'success', 'data' => $contract]);
    })->name('getContract');
    Route::get('/create', Create::class)->name('create');
    Route::get('/{delivery}', Show::class)->name('show');

});