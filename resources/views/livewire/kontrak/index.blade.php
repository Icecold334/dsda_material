<div>
    <div data-grid data-api="{{ route('kontrak.json') }}" data-columns='[
                { "name": "Nomor Kontrak", "id": "nomor" },
                { "name": "", "id": "action" }
            ]' data-limit="10" data-default='{"status":"active"}' wire:ignore></div>
</div>