<div class="space-y-4">
    <div class="grid grid-cols-2">
        <div>
            <div class="text-3xl font-semibold">Adendum Kontrak #{{ $this->amendment->amend_version }}</div>
            <div class="text-sm text-gray-600 mt-1">Kontrak: {{ $this->contract->nomor }}</div>
        </div>
        <div class="text-right">
            <a href="{{ route('contract.show', $this->contract->id) }}" wire:navigate
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <x-card title="Informasi Adendum">
            <table class="table-auto w-full text-md space-y-2">
                <tr>
                    <td class="font-semibold w-1/2">Nomor Adendum</td>
                    <td>{{ $this->amendment->nomor }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Versi</td>
                    <td>{{ $this->amendment->amend_version }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Total</td>
                    <td>Rp {{ number_format($this->amendment->total, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Status</td>
                    <td>
                        <span
                            class="bg-{{ $this->amendment->status_color }}-600 text-{{ $this->amendment->status_color }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $this->amendment->status_text }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Tanggal Dibuat</td>
                    <td>{{ $this->amendment->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </x-card>

        <x-card title="Informasi Kontrak">
            <table class="table-auto w-full text-md space-y-2">
                <tr>
                    <td class="font-semibold w-1/2">Nomor Kontrak</td>
                    <td>{{ $this->contract->nomor }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Sudin</td>
                    <td>{{ $this->contract->sudin->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold">Tanggal Mulai</td>
                    <td>{{ $this->contract->tanggal_mulai ? \Carbon\Carbon::parse($this->contract->tanggal_mulai)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="font-semibold">Tanggal Selesai</td>
                    <td>{{ $this->contract->tanggal_selesai ? \Carbon\Carbon::parse($this->contract->tanggal_selesai)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            </table>
        </x-card>
    </div>

    <x-card title="Daftar Barang">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->amendment->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->item->code }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $item->item->category->name }} | {{ $item->item->spec }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                {{ number_format($item->qty, 2) }} {{ $item->item->category->unit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                Rp {{ number_format($item->price, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                Rp {{ number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada item
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold">Total:</td>
                        <td class="px-6 py-3 text-right font-semibold">
                            Rp {{ number_format($this->amendment->total, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-card>
</div>