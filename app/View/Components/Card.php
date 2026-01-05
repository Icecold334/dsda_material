<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public string $title;

    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function render(): View|Closure|string
    {
        return <<<'blade'
<div {{ $attributes->merge(['class' => 'w-full bg-white ring-1 ring-black/10 rounded-lg shadow-sm']) }}>
        <div class="flex flex-wrap text-md font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-primary-100 " >
                <div class="inline-block py-2 px-4 text-primary-800 rounded-ss-lg text-lg">{{$title ?? 'Card'}}</div>
        </div>
        <div >
            <div class="p-4 bg-white rounded-b-lg  ">
                    {{ $slot }}
            </div>
        </div>
    </div>
blade;
    }
}
