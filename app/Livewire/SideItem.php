<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SideItem extends Component
{
    public $title, $href = '#', $icon = null, $collapsable = false, $items = [];

    #[Computed]
    public function active(): bool
    {
        // For collapsable menus, check if any child item is active
        if ($this->collapsable && !empty($this->items)) {
            foreach ($this->items as $item) {
                $itemHref = $item['href'] ?? '';
                // Parse the path only not full URL
                $itemPath = parse_url($itemHref, PHP_URL_PATH);
                $itemPath = ltrim($itemPath, '/');

                if ($itemPath && (Request::is($itemPath) || Request::is($itemPath . '/*'))) {
                    return true;
                }
            }
            return false;
        }

        // For normal menu items
        if ($this->href && $this->href !== '#') {
            $path = parse_url($this->href, PHP_URL_PATH);
            $path = ltrim($path, '/');

            return Request::is($path) || Request::is($path . '/*');
        }

        return false;
    }

    public function render()
    {
        return view('livewire.side-item');
    }
}
