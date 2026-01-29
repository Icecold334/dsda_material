<div class="space-y-4" x-data="{ fileCount: 0 }" @file-count-updated.window="fileCount = $event.detail.count">
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="flex items-center gap-3">
                <div class="text-3xl font-semibold"> Rencana Anggaran Biaya
                    @if($this->currentVersion instanceof \App\Models\RabAmendment)
                        {{ $this->currentVersion->nomor }}
                    @else
                        {{ $rab->nomor }}
                    @endif
                </div>
                <div>
                    @if($this->currentVersion instanceof \App\Models\RabAmendment)
                        <span
                            class="bg-{{ $this->currentVersion->status_color }}-600 text-{{ $this->currentVersion->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $this->currentVersion->status_text }}
                        </span>
                    @else
                        <span
                            class="bg-{{ $rab->status_color }}-600 text-{{ $rab->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $rab->status_text }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-right flex items-center justify-end gap-2">
            @if ($rab->hasApprovedAmendments())
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Versi:</label>
                    <select wire:model.live="showVersion"
                        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm px-3 py-2">
                        <option value="latest">
                            <i class="fa-solid fa-star"></i> Versi Terbaru
                        </option>
                        <option value="original">RAB Asli</option>
                        @foreach ($this->amendments as $amendment)
                            <option value="{{ $amendment->id }}">
                                Adendum #{{ $amendment->amend_version }} ({{ $amendment->status_text }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <a href="{{ route('rab.amendment.create', $rab->id) }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800 focus:ring-primary-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Adendum
            </a>
            <x-secondary-button @click="$dispatch('open-modal', 'rab-information-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi RAB
            </x-secondary-button>
            <x-secondary-button @click="$dispatch('open-modal', 'lampiran-modal')" type="button">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Lampiran
                <span
                    class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-indigo-600 rounded-full"
                    x-show="fileCount > 0" x-text="fileCount">
                </span>
            </x-secondary-button>
            <x-button variant="secondary" href="{{ route('rab.index') }}" wire:navigate>
                Kembali
            </x-button>
        </div>
    </div>

    <div>
        <x-card title="Daftar Barang {{ $showVersion === 'original' ? '(Versi Asli)' : ($showVersion === 'latest' && $rab->hasApprovedAmendments() ? '(Versi Terbaru)' : '') }}">
            @if($this->currentVersion instanceof \App\Models\RabAmendment)
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($item->qty, 2) }}
                                        {{ $item->item->category->unit->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Show original RAB items -->
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
                            @foreach($rab->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->item->code }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->item->category->name }} | {{ $item->item->spec }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($item->qty, 2) }}
                                        {{ $item->item->category->unit->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    </div>

    <!-- Modal Components -->
    <livewire:components.rab-information-modal mode="show" :key="'rab-info-modal-' . $rab->id" />

    <!-- Modal Lampiran -->
    <livewire:components.document-upload mode="show" modelType="App\Models\Rab" :modelId="$rab->id"
        category="lampiran_rab" label="Lampiran RAB" :multiple="true" accept="image/*,.pdf,.doc,.docx"
        modalId="lampiran-modal" :key="'doc-show-lampiran-' . $rab->id" />
</div>