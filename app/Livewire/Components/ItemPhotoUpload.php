<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RequestItem;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class ItemPhotoUpload extends Component
{
    use WithFileUploads;

    public $requestItemId;
    public $photo;
    public $existingPhoto;
    public $isUploading = false;
    public $showConfirmModal = false;
    public $tempPhoto;

    protected $listeners = ['refreshPhoto' => '$refresh'];

    public function mount($requestItemId)
    {
        $this->requestItemId = $requestItemId;
        $this->loadExistingPhoto();
    }

    public function loadExistingPhoto()
    {
        $requestItem = RequestItem::find($this->requestItemId);
        if ($requestItem) {
            $this->existingPhoto = $requestItem->photo;
        }
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:5120', // 5MB Max
        ]);

        $this->tempPhoto = $this->photo;
        $this->showConfirmModal = true;
    }

    public function confirmUpload()
    {
        $this->isUploading = true;

        try {
            $requestItem = RequestItem::find($this->requestItemId);

            if (!$requestItem) {
                throw new \Exception('Item tidak ditemukan');
            }

            // Hapus foto lama jika ada
            if ($this->existingPhoto) {
                Storage::disk('public')->delete($this->existingPhoto->file_path);
                $this->existingPhoto->delete();
            }

            // Upload foto baru
            $path = $this->tempPhoto->store('item-photos', 'public');

            Document::create([
                'documentable_type' => RequestItem::class,
                'documentable_id' => $this->requestItemId,
                'category' => 'item_photo',
                'file_path' => $path,
                'file_name' => $this->tempPhoto->getClientOriginalName(),
                'mime_type' => $this->tempPhoto->getMimeType(),
                'size_kb' => $this->tempPhoto->getSize() / 1024,
                'user_id' => auth()->id(),
            ]);

            $this->loadExistingPhoto();
            $this->reset(['photo', 'tempPhoto', 'showConfirmModal', 'isUploading']);

            $this->dispatch('photoUploaded');

            // Dispatch to window for page reload
            $this->js('window.dispatchEvent(new CustomEvent("photoUploaded"))');

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Foto berhasil diupload'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Gagal!',
                'text' => $e->getMessage()
            ]);
        } finally {
            $this->isUploading = false;
        }
    }

    public function cancelUpload()
    {
        $this->reset(['photo', 'tempPhoto', 'showConfirmModal']);
    }

    public function render()
    {
        return view('livewire.components.item-photo-upload');
    }
}
