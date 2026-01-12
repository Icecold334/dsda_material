<div x-data="{ count: {{ count($files) }} }"
     x-init="$dispatch('file-count-updated', count)"
     @file-count-updated-internal.window="count = $event.detail.count; $dispatch('file-count-updated', count)">
    <!-- Modal -->
    <x-modal name="{{ $modalId }}" maxWidth="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ $label }}
            </h2>

            @if ($mode === 'create')
                <!-- Create Mode: Upload Interface -->
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <label
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Pilih File
                                <input type="file" wire:model="files" {{ $multiple ? 'multiple' : '' }} accept="{{ $accept }}"
                                    class="hidden" />
                            </label>

                            @if (count($files) > 0)
                                <span class="text-sm text-gray-600">{{ count($files) }} file dipilih</span>
                            @endif
                        </div>

                        @error('files.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Loading indicator -->
                        <div wire:loading wire:target="files" class="mt-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses file...
                            </div>
                        </div>
                    </div>

                    <!-- Preview uploaded files -->
                    @if (count($files) > 0)
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-700">File yang akan diupload:</p>
                            <ul class="divide-y divide-gray-200 border border-gray-200 rounded-md max-h-96 overflow-y-auto">
                                @foreach ($files as $index => $file)
                                    <li class="flex items-center justify-between py-3 px-4 hover:bg-gray-50">
                                        <div class="flex items-center min-w-0 flex-1">
                                            <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <div class="ml-3 min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $file->getClientOriginalName() }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ number_format($file->getSize() / 1024, 2) }} KB
                                                </p>
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeFile({{ $index }})"
                                            class="ml-4 flex-shrink-0 text-red-600 hover:text-red-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @elseif($mode === 'show')
                <!-- Show Mode: Display existing documents -->
                <div>
                    @if (count($existingDocuments) > 0)
                        <ul class="divide-y divide-gray-200 border border-gray-200 rounded-md max-h-96 overflow-y-auto">
                            @foreach ($existingDocuments as $document)
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                    class="flex items-center justify-between py-3 px-4 hover:bg-gray-50">
                                    <div class="flex items-center min-w-0 flex-1">
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $document->file_name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $document->size_kb }} KB
                                                @if ($document->user)
                                                    • Diupload oleh {{ $document->user->name }}
                                                @endif
                                                • {{ $document->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </ul>
                    @else
                        <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Tidak ada dokumen</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Tutup
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>