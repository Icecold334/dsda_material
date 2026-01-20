<div class="p-4 border rounded-lg">
    @if ( true)
    @if (session('success'))
    <div class="mb-3 p-2 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
    @endif

    @error('approve')
    <div class="mb-3 p-2 rounded bg-red-100 text-red-800">
        {{ $message }}
    </div>
    @enderror

    <div class="flex items-center gap-2 mt-2">
        <button type="button" wire:click="approve" @disabled(! $canApprove || ! $extraReady)
            class="px-3 py-2 rounded bg-blue-600 text-white disabled:opacity-50">
            Approve
        </button>

        <button type="button" wire:click="$toggle('showRejectForm')" @disabled(! $canApprove)
            class="px-3 py-2 rounded bg-red-600 text-white disabled:opacity-50">
            Tolak
        </button>
    </div>


    <div class="mt-3 text-sm text-gray-600">
        @if(!$extraReady)
        {{ $extraError }}
        @endif
        {{-- @if($isFinal)
        Status approval: final
        @else
        Status approval: sedang berjalan
        @endif --}}
    </div>

    @if($showRejectForm)
    <div class="mt-3 p-3 border rounded bg-gray-50">
        <label class="block text-sm font-semibold mb-1">
            Alasan Penolakan
        </label>

        <textarea wire:model.defer="rejectReason" class="w-full border rounded p-2" rows="3"></textarea>

        @error('reject')
        <div class="text-sm text-red-600 mt-1">
            {{ $message }}
        </div>
        @enderror

        <div class="flex gap-2 mt-2">
            <button type="button" wire:click="reject" class="px-3 py-1 rounded bg-red-600 text-white">
                Konfirmasi Tolak
            </button>

            <button type="button" wire:click="$set('showRejectForm', false)" class="px-3 py-1 rounded bg-gray-300">
                Batal
            </button>
        </div>
    </div>
    @endif

    @endif
</div>