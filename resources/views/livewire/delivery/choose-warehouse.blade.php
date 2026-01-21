<div>
    <x-modal name="choose-warehouse" :dismissable="false">
        <div class="p-6 space-y-6">

            {{-- Header --}}
            <div class="flex items-center gap-2">
                <div class="text-lg text-primary-700">
                    <x-button variant="secondary" type="button"
                        x-on:click="($dispatch('close-modal','choose-warehouse'),$dispatch('open-modal','input-contract-number'))"><i
                            class="fa-solid fa-circle-chevron-left"></i></x-button>
                </div>
                <div>
                    <div class="font-semibold text-2xl">Pilih Gudang</div>
                    <div class="text-sm text-gray-500">Silakan pilih salah satu gudang</div>
                </div>
            </div>

            <div class="flex flex-row overflow-x-auto max-w-full gap-2 py-4 px-1">
                @foreach ($warehouses as $warehouse)
                    <x-card id="warehouse{{ $warehouse->id }}"
                        class="select-none min-w-96 min-h-64 py-6 transition duration-200 hover:bg-primary-200 active:bg-primary-300 hover:cursor-pointer hover:scale-[1.02] ">
                        <div class="text-2xl font-medium">{{ $warehouse->name }}</div>
                        <div class="font-normal">{{ fake()->paragraph }}</div>
                    </x-card>
                    @push('scripts')
                        <script type="module">
                            document.getElementById("warehouse{{ $warehouse->id }}").addEventListener("click", async () => {
                                var wareHouseId = "{{ $warehouse->id }}"
                                var wareHouseName = "{{ $warehouse->name }}"
                                showConfirm(
                                    {

                                        type: "question",
                                        title: "Pilih " + wareHouseName + "?",
                                        text: "Apakah Anda yakin ingin memilih gudang ini sebagai lokasi pengiriman?",
                                        confirmButtonText: "Lanjutkan",
                                        cancelButtonText: "Batal",
                                        onConfirm: (e) => {
                                            window.Livewire.dispatch('proceedWarehouse', { warehouse: wareHouseId });
                                            window.Livewire.dispatch("close-modal", 'choose-warehouse');
                                        }
                                    }
                                )
                            });
                        </script>
                    @endpush
                @endforeach
            </div>


        </div>
    </x-modal>
</div>