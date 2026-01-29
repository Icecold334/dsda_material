<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Item;
use App\Models\RequestModel;
use App\Models\RequestItem;
use App\Models\StockTransaction;

class Test extends Component
{

    public $requests = [];
    public $requestItems = [];
    public $stockTransactions = [];
    public $items = [];
    public $filterdate;
    public $latestRequests = [];

    public function mount()
    {
        $this->stockTransactions = StockTransaction::all();
        $this->items = Item::all();
        $this->prepareLatestRequest();
    }

    private function getInitials($name)
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
        return $labels[$status] ?? ucfirst($status);
    }

    private function getStatusColor($status)
    {
        $colors = [
            'draft' => 'gray',
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];
        return $colors[$status] ?? 'info';
    }

    public function prepareLatestRequest()
    {
        $this->requests = RequestModel::all();
        $this->requestItems = RequestItem::all();

        // Fetch latest 10 requests with user relationship
        $this->latestRequests = RequestModel::with(['user', 'user.division'])
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'nomor' => $request->nomor ?? 'N/A',
                    'user_name' => $request->user->name ?? 'Unknown',
                    'user_initials' => $this->getInitials($request->user->name ?? 'U'),
                    'user_unit' => $request->user->division->name ?? 'N/A',
                    'created_at' => $request->created_at,
                    'created_at_human' => $request->created_at->diffForHumans(),
                    'status' => $request->status,
                    'status_label' => $this->getStatusLabel($request->status),
                    'status_color' => $this->getStatusColor($request->status),
                ];
            });
    }

    public function render()
    {
        return view('livewire.test');
    }
}
