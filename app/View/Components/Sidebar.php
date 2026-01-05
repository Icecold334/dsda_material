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
                <livewire:side-item title="Permintaan" icon="users" :collapsable="true" :items="[
                    ['title' => 'Menggunakan RAB', 'href' => route('permintaan.rab.index')],
                        ['title' => 'Tanpa RAB', 'href' => '/users'],
                    ]" />
                <livewire:side-item title="User Management 2" icon="users" :collapsable="true" :items="[
                        ['title' => 'List Users 2', 'href' => '/users'],
                        ['title' => 'Roles 2', 'href' => '/roles'],
                        ['title' => 'Permissions 2', 'href' => '/permissions']
                    ]" />
            </ul>
</div>
blade;
    }
}
