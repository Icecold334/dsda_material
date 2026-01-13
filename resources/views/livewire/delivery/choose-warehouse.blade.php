<div>
    <x-modal name="choose-warehouse">
        <div class="p-6 space-y-6">

            {{-- Header --}}
            <div>
                <h2 class="text-3xl font-semibold text-gray-900">
                    Pilih Gudang Pengiriman
                </h2>
            </div>




            {{-- Footer --}}
            {{-- <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button"
                    class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5"
                    x-on:click="$dispatch('close-modal', 'confirm-contract')">
                    {{ $showDetail ? "Tutup":"Tidak" }}
                </button>

                @if (!$showDetail)
                @if ($isApi)
                <button type="button" x-on:click="
                $dispatch('proceedCreateContract', {data:{{ json_encode($contractData) }}, isApi:true})
                ,$dispatch('close-modal', 'confirm-contract')
                ,$dispatch('close-modal', 'input-contract-number')"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Ya, Lanjutkan
                </button>
                @else
                <button type="button"
                    x-on:click="$dispatch('proceedCreateContract', {data:{{ json_encode($contractData) }}, isApi:false})"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Ya, Lanjutkan
                </button>
                @endif
                @endif
            </div> --}}

        </div>
    </x-modal>
</div>