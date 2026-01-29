<div class="flex items-start gap-[62px]">
    @if(file_exists(public_path('images/logo-jakarta.png')))
        <img src="{{ public_path('images/logo-jakarta.png') }}" alt="Logo Jakarta" class="w-20 h-auto object-contain" />
    @endif

    <div class=" text-center">
        <h3 class="text-xs">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h3>
        <h3 class="text-xs font-bold">DINAS SUMBER DAYA AIR</h3>
        <h3 class="text-sm font-bold">SUKU DINAS SUMBER DAYA AIR</h3>
        <h3 class="text-sm font-bold uppercase">KOTA ADMINISTRASI {{$sudin->name ?? "-"}}</h3>
        <div class="text-[10px]">{{$sudin->address ?? "-"}}, Telp.
            {{ $sudin->phone ?? "-" }}, Kode Pos : {{ $sudin->postal_code ?? "-" }}
        </div>
        <div class="text-[10px] tracking-widest">JAKARTA</div>
    </div>
</div>