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
        return <<<'blade'
<div>
   <ul class="space-y-2 font-medium">
                <livewire:side-item title="Dashboard" href="/" />
                <livewire:side-item title="Kontrak" href="{{ route('kontrak.index') }}" />
                <livewire:side-item title="RAB" href="{{ route('rab.index') }}" />
                <livewire:side-item title="Master Data" icon="users" :collapsable="true" :items="[
                        ['title' => 'Driver', 'href' => route('driver.index')],
                        ['title' => 'Security', 'href' => route('security.index')],
                        ['title' => 'Sudin', 'href' => route('sudin.index')],
                        ['title' => 'Kecamatan', 'href' => route('district.index')],
                        ['title' => 'Gudang', 'href' => route('warehouse.index')],
                    ]" />
            </ul>
</div>
blade;
    }
}
