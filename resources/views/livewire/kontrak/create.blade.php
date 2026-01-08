<div>
    <div class="grid grid-cols-2 ">
        <div class="">
            <div class="text-3xl font-semibold"> Tambah Kontrak</div>
        </div>
        <div class="text-right">
            <a href="{{ route('kontrak.index') }}" wire:navigate
                class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2  focus:outline-none">Kembali</a>
        </div>
    </div>
    <x-modal name="input-nomor-kontrak" :dismissable="false">
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-2">
                <div class="text-lg text-primary-700">
                    <a href="{{ route('kontrak.index') }}" wire:navigate><i
                            class="fa-solid fa-circle-chevron-left"></i></a>
                </div>
                <div class="font-semibold text-2xl">Masukkan Nomor Kontrak</div>
            </div>

            {{-- <form method="GET" action="{{ route('kontrak.emonev') }}"> --}}
                <div class="flex">
                    <input type="text" id="nomorKontrak"
                        class="rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 block flex-1 text-sm p-2.5"
                        placeholder="Masukkan Nomor Kontrak" required>

                    <input type="text" id="tahunKontrak"
                        class="rounded-none bg-gray-50 border border-gray-300 text-gray-900 block w-20 text-sm p-2.5"
                        placeholder="Tahun" required>

                    <button type="button" id="btnCari" class=" inline-flex items-center px-3 text-sm text-white bg-primary-600 hover:bg-primary-800
                        rounded-e-md transition">
                        Cari
                    </button>
                </div>
                {{--
            </form> --}}
        </div>
    </x-modal>
</div>