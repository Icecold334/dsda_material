<?php

namespace App\Livewire\Stock;

use App\Exports\StockOpnameTemplateExport;
use App\Imports\StockOpnameImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class StokOpnameModal extends Component
{
    use WithFileUploads;

    public $warehouse;
    public $file;
    public $uploadedFile;
    public $results = [];

    protected $listeners = ['openStokOpnameModal'];

    public function openStokOpnameModal($warehouseId)
    {
        $this->warehouse = \App\Models\Warehouse::findOrFail($warehouseId);
        $this->reset(['file', 'uploadedFile', 'results']);
        $this->dispatch('open-modal', 'stok-opname');
    }

    public function downloadTemplate()
    {
        $filename = 'template_stok_opname_' . $this->warehouse->name . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new StockOpnameTemplateExport($this->warehouse->id),
            $filename
        );
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'File harus diupload',
            'file.mimes' => 'File harus berformat Excel (xlsx, xls)',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        $this->uploadedFile = $this->file;
        session()->flash('message', 'File berhasil diupload. Klik tombol "Jalankan Penyesuaian" untuk memproses.');
    }

    public function processStokOpname()
    {
        if (!$this->uploadedFile) {
            session()->flash('error', 'Silakan upload file terlebih dahulu');
            return;
        }

        try {
            $import = new StockOpnameImport($this->warehouse->id, auth()->id());
            Excel::import($import, $this->uploadedFile->getRealPath());

            $this->results = $import->getResults();

            $successCount = collect($this->results)->where('status', 'success')->count();
            $errorCount = collect($this->results)->where('status', 'error')->count();

            session()->flash('message', "Stok opname berhasil diproses. Berhasil: {$successCount}, Gagal: {$errorCount}");

            // Reset file setelah diproses
            $this->reset(['file', 'uploadedFile']);

            // Refresh halaman stock
            $this->dispatch('refresh-stock');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.stock.stok-opname-modal');
    }
}
