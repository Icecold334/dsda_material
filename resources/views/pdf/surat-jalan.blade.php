<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="flex items-start gap-[62px]">
        @if(file_exists(public_path('images/logo-jakarta.png')))
            <img src="{{ public_path('images/logo-jakarta.png') }}" alt="Logo Jakarta" class="w-20 h-auto object-contain" />
        @endif

        <div class=" text-center">
            <h3 class="text-xs">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h3>
            <h3 class="text-xs font-bold">DINAS SUMBER DAYA AIR</h3>
            <h3 class="text-sm font-bold">SUKU DINAS SUMBER DAYA AIR</h3>
            <h3 class="text-sm font-bold uppercase">KOTA ADMINISTRASI {{$permintaan->sudin->name ?? "-"}}</h3>
            <div class="text-[10px]">{{$permintaan->sudin->address ?? "-"}}</div>
            <div class="text-[10px] tracking-widest">JAKARTA</div>
        </div>
    </div>

    <!-- Document Title -->
    <div class="text-center font-bold text-[11px] mt-5">
        SURAT JALAN
    </div>
    <div class="text-center text-[11px] mb-5">
        <span class="font-bold">Nomor :</span> <em>{{ $permintaan->nomor ?? '(Nomor Surat Jalan)' }}</em>
    </div>

    <!-- Information Section -->
    <div class="mb-5 text-[11px]">
        <div class="flex mb-1.5">
            <div class="w-40">Jenis Pekerjaan</div>
            <div class="w-5">:</div>
            <div class="flex-1">{{ $permintaan->name }}</div>
        </div>
        <div class="flex mb-1.5">
            <div class="w-40">Lokasi Pekerjaan</div>
            <div class="w-5">:</div>
            <div class="flex-1">{{ $permintaan->address }}, Kelurahan {{ $permintaan->subdistrict->name ?? '-' }},
                Kecamatan {{ $permintaan->district->name ?? '-' }}
            </div>
        </div>
        <div class="flex mb-1.5">
            <div class="w-40">Pemohon</div>
            <div class="w-5">:</div>
            <div class="flex-1">{{ $permintaan->user->name ?? '-' }} Selaku <span class="italic">(Jabatan)</span></div>
        </div>
        <div class="flex mb-1.5">
            <div class="w-40">Nopol Kendaraan</div>
            <div class="w-5">:</div>
            <div class="flex-1">{{ $permintaan->nopol ?? '-' }}</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="w-full border-collapse my-5 text-[11px]">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-black p-2 text-center w-12">No</th>
                <th class="border border-black p-2 text-center">Nama Barang</th>
                <th class="border border-black p-2 text-center">Spesifikasi</th>
                <th class="border border-black p-2 text-center w-32">Volume</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permintaan->items as $index => $item)
                <tr>
                    <td class="border border-black p-2 text-center">{{ $index + 1 }}</td>
                    <td class="border border-black p-2">{{ $item->item->category->name ?? '-' }}</td>
                    <td class="border border-black p-2">{{ $item->item->spec ?? '-' }}</td>
                    <td class="border border-black p-2">
                        {{ number_format($item->qty_request, 2, ',', '.') }}
                        {{ $item->item->category->unit->name ?? '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border border-black p-2 text-center">
                        Tidak ada data barang
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Date and Location -->
    <div class="text-right mt-5 mb-3 text-[11px]">
        Jakarta,
        <em>{{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->locale('id')->translatedFormat('d F Y') }}</em>
    </div>
</body>

</html>