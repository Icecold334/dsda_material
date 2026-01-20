<li>
    {{-- COLLAPSABLE MENU --}}
    @if ($collapsable)
        <button type="button"
            class="flex items-center w-full p-2 rounded-lg group transition duration-150
                    {{ $this->active ? 'bg-gray-100 text-primary-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-primary-600' }}"
            data-collapse-toggle="collapse-{{ Str::slug($title) }}">



            <span class="flex-1 ml-3 text-left">{{ $title }}</span>

            <svg class="w-4 h-4 transition-transform group-[.collapsed]:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11l3.71-3.77a.75.75 0 111.08 1.04l-4.25 4.33a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" />
            </svg>
        </button>

        <ul id="collapse-{{ Str::slug($title) }}" class="hidden py-2 space-y-1 bg-white rounded-lg mt-2">
            @foreach ($items as $item)
                @php
                    $itemPath = ltrim(parse_url($item['href'], PHP_URL_PATH), '/');
                    $isChildActive = $itemPath && (Request::is($itemPath) || Request::is($itemPath . '/*'));
                @endphp
                <li>
                    <a href="{{ $item['href'] }}" wire:navigate class="flex items-center py-2 mx-2 px-4 rounded-lg transition duration-200
                                            {{ $isChildActive
                    ? 'bg-primary-600 text-white font-semibold'
                    : 'text-primary-600 hover:bg-primary-600 hover:text-white' }}">
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- NORMAL MENU --}}
    @else
        <a href="{{ $href }}" wire:navigate
            class="flex items-center p-2 rounded-lg transition duration-150
                                            {{ $this->active ? 'bg-gray-100 hover:text-primary-600 text-primary-600 font-semibold' : 'text-white hover:bg-gray-100 hover:text-primary-600' }}">



            <span class="ml-3">{{ $title }}</span>
        </a>
    @endif
</li>