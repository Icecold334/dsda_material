<?php

namespace App\Livewire\Delivery;

use App\Services\StockLedgerService;
use Livewire\Component;
use App\Models\Delivery;
use Livewire\Attributes\Title;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    #[Title('Detail Delivery')]
    public Delivery $delivery;

    protected $listeners = [
        'approvalExtraCheckRequested' => 'handleExtraCheck',
        'approvalRejected' => 'onApprovalRejected',
        'approvalComplete' => 'approvalComplete'
    ];

    public function handleExtraCheck()
    {
        // dd($this->delivery->items->first());
        $ready = true;
        $message = '';

        $this->dispatch('approvalExtraCheckResult', ready: $ready, message: $message);
    }
    public function approvalComplete()
    {
        $this->delivery->status = 'completed';
        $this->delivery->save();

        foreach ($this->delivery->items as $data) {
            app(StockLedgerService::class)->in([
                'qty' => $data['qty'],
                'ref_type' => Delivery::class,
                'ref_id' => $this->delivery->id,
                'item_id' => $data['item_id'],
                'warehouse_id' => $this->delivery->warehouse_id,
                'sudin_id' => $this->delivery->sudin_id,
                'user_id' => auth()->id(),
            ]);

        }
    }

    public function openInformasiModal()
    {
        $this->dispatch('open-modal', 'detail-informasi-modal');
    }

    public function openSuratJalanModal()
    {
        $this->dispatch('open-modal', 'surat-jalan-modal');
    }

    public function openFotoPengirimanModal()
    {
        $this->dispatch('open-modal', 'foto-pengiriman-modal');
    }

    public function render()
    {
        return view('livewire.delivery.show');
    }
}
