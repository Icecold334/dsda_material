<div class="p-4 border rounded-lg">
    <div class="mb-2 font-semibold">Approval</div>

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

    <div class="flex items-center gap-2">
        <button type="button" wire:click="approve" @disabled(! $canApprove || ! $extraReady)
            class="px-3 py-2 rounded bg-blue-600 text-white disabled:opacity-50">
            Approve
        </button>

        @if(! $extraReady)
        <span class="text-sm text-red-600">
            {{ $extraError ?: 'Syarat level ini belum lengkap' }}
        </span>
        @endif
    </div>

    <div class="mt-3 text-sm text-gray-600">
        @if($isFinal)
        Status approval: final
        @else
        Status approval: sedang berjalan
        @endif
    </div>
</div>