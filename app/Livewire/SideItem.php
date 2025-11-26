<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

class SideItem extends Component
{
    public $title, $href = '#', $icon = null, $active = false, $collapsable = false, $items = [];

    public function mount()
    {
        // Auto-active jika href sama dengan URL sekarang
        if (!$this->active && $this->href && $this->href !== '#') {
            $this->active = Request::is(ltrim($this->href, '/'))
                || Request::is(ltrim($this->href, '/') . '/*');
        }
    }

    public function render()
    {
        return view('livewire.side-item');
    }
}
