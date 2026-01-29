<div class="space-y-4">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="flex items-center gap-3">
                <div class="text-3xl font-semibold">Kontrak
                    @if($this->currentVersion instanceof \App\Models\ContractAmendment)
                        {{ $this->currentVersion->nomor }}
                    @else
                        {{ $contract->nomor }}
                    @endif
                </div>
                <div>
                    @if($this->currentVersion instanceof \App\Models\ContractAmendment)
                        <span
                            class="bg-{{ $this->currentVersion->status_color }}-600 text-{{ $this->currentVersion->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $this->currentVersion->status_text }}
                        </span>
                    @else
                        <span
                            class="bg-{{ $contract->status_color }}-600 text-{{ $contract->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $contract->status_text }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-right flex items-center justify-end gap-2">
            @if ($contract->hasApprovedAmendments())
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Versi:</label>
                    <select wire:model.live="showVersion"
                        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm px-3 py-2">
                        <option value="latest">
                            <i class="fa-solid fa-star"></i> Versi Terbaru
                        </option>
                        <option value="original">Kontrak Asli</option>
                        @foreach ($this->amendments as $amendment)
                            <option value="{{ $amendment->id }}">
                                Adendum #{{ $amendment->amend_version }} ({{ $amendment->status_text }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <a href="{{ route('contract.amendment.create', $contract->id) }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800 focus:ring-primary-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Adendum
            </a>
            <a href="{{ route('contract.index') }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">Kembali</a>
        </div>
    </div>

    <x-card title="Dokumen Kontrak" class="hidden">

        <ul class="divide-y divide-default">
            @for ($i = 0; $i < 5; $i++)
                <li class="p-1">
                    <div class="flex items-center space-x-4 rtl:space-x-reverse">
                        <div class="shrink-0 text-success-600">
                            <i class="fa-solid fa-file"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-heading truncate">
                                {{ fake()->sentence }}
                            </p>
                        </div>
                    </div>
                </li>
            @endfor
        </ul>

    </x-card>

    <x-card
        title="Daftar Barang {{ $showVersion === 'original' ? '(Versi Asli)' : ($showVersion === 'latest' && $contract->hasApprovedAmendments() ? '(Versi Terbaru)' : '') }}">
        @if($this->currentVersion instanceof \App\Models\ContractAmendment)
            <!-- Show amendment items -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($this->currentVersion->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->item->code }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->item->category->name }} | {{ $item->item->spec }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ (int) $item->qty }}
                                    {{ $item->item->category->unit->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Show original contract items -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contract->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->item->code }}</td>
                                <td class="px-6 py-4 text-sm">{{ $item->item->category->name }} | {{ $item->item->spec }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ (int) $item->qty }}
                                    {{ $item->item->category->unit->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </x-card>
</div>