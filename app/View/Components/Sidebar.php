<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'BLADE'
<div>
   <ul class="space-y-2 font-medium">
                <livewire:side-item title="Dashboard" href="/" />
                <livewire:side-item title="Kontrak" href="{{ route('contract.index') }}" />
                <livewire:side-item title="RAB" href="{{ route('rab.index') }}" />
                <livewire:side-item title="Stok" href="{{ route('stock.index') }}" />
                <livewire:side-item title="Pengiriman Barang" href="{{ route('delivery.index') }}" />
                            <livewire:side-item title="Permintaan Barang" icon="users" :collapsable="true" :items="[
                                            ['title' => 'Menggunakan RAB', 'href' => route('permintaan.rab.index')],
                                                ['title' => 'Tanpa RAB', 'href' => route('permintaan.nonRab.index')],
                                            ]" />
                <livewire:side-item title="Master Data" icon="users" :collapsable="true" :items="[
                        ['title' => 'Driver', 'href' => route('driver.index')],
                        ['title' => 'Security', 'href' => route('security.index')],
                        ['title' => 'Sudin', 'href' => route('sudin.index')],
                        ['title' => 'Kecamatan', 'href' => route('district.index')],
                        ['title' => 'Gudang', 'href' => route('warehouse.index')],
                        ['title' => 'Kategori Barang', 'href' => route('item-category.index')],
                        ['title' => 'Barang', 'href' => route('item.index')],
                        ['title' => 'Pengguna', 'href' => route('user.index')],
                    ]" />
            </ul>
</div>
BLADE;
    }
}
