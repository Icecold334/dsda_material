<?php

namespace App\Livewire\Components;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUpload extends Component
{
    use WithFileUploads;

    // Props
    public $mode = 'create'; // 'create' or 'show'
    public $modelType; // Namespace model (e.g., App\Models\RequestModel)
    public $modelId; // ID dari model (optional, untuk show mode)
    public $category; // Kategori dokumen (e.g., 'surat_permintaan', 'lampiran_pendukung')
    public $label = 'Upload Dokumen'; // Label yang ditampilkan
    public $multiple = false; // Allow multiple upload
    public $accept = '*'; // File types accepted
    public $modalId = 'document-upload-modal'; // Unique modal ID
    public $autoUpload = false; // If true, show upload button for immediate upload; if false, wait for form save

    // State
    public $files = []; // For upload (create mode)
    public $existingDocuments = []; // For display (show mode)
    public $uploadProgress = [];

    protected $listeners = [
        'refreshDocuments' => 'loadExistingDocuments',
        'saveDocuments' => 'saveDocuments',
    ];

    public function mount()
    {
        if ($this->mode === 'show' && $this->modelId) {
            $this->loadExistingDocuments();
        }
    }

    public function loadExistingDocuments()
    {
        if ($this->modelId && $this->modelType) {
            $this->existingDocuments = Document::where('documentable_type', $this->modelType)
                ->where('documentable_id', $this->modelId)
                ->where('category', $this->category)
                ->with('user')
                ->get();
        }
    }

    public function updatedFiles()
    {
        // Validate files
        $rules = [
            'files.*' => 'file|max:10240', // 10MB max
        ];

        $this->validate($rules);

        // Dispatch event to update file count in parent
        $this->dispatch('fileCountUpdated', count: count($this->files))->self();
        $this->js("window.dispatchEvent(new CustomEvent('file-count-updated-internal', { detail: { count: " . count($this->files) . " } }));");
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files); // Re-index array

            // Dispatch event to update file count
            $this->dispatch('fileCountUpdated', count: count($this->files))->self();
            $this->js("window.dispatchEvent(new CustomEvent('file-count-updated-internal', { detail: { count: " . count($this->files) . " } }));");
        }
    }

    public function saveDocumentsToParent()
    {
        if (empty($this->files)) {
            return;
        }

        // Dispatch event to parent to save documents
        $this->dispatch('saveDocuments', $this->modelId);

        // Close modal after dispatching
        $this->dispatch('close');
    }

    public function deleteDocument($documentId)
    {
        $document = Document::find($documentId);

        if ($document) {
            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete record
            $document->delete();

            $this->loadExistingDocuments();

            $this->dispatch('documentDeleted', documentId: $documentId);
        }
    }

    public function downloadDocument($documentId)
    {
        $document = Document::find($documentId);

        if ($document && Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }
    }

    /**
     * Save uploaded files to database and storage
     * Called from parent component after model is saved
     */
    public function saveDocuments($modelId)
    {
        if (empty($this->files)) {
            return;
        }

        try {
            $folderName = $this->getFolderName();

            foreach ($this->files as $file) {
                $originalFileName = $file->getClientOriginalName();
                // Generate random filename while preserving extension
                $extension = $file->getClientOriginalExtension();
                $randomFileName = \Illuminate\Support\Str::uuid() . '.' . $extension;

                // Store file with random name
                $filePath = $file->storeAs($folderName, $randomFileName, 'public');

                Document::create([
                    'documentable_type' => $this->modelType,
                    'documentable_id' => $modelId,
                    'category' => $this->category,
                    'file_path' => $filePath,
                    'file_name' => $originalFileName,
                    'mime_type' => $file->getMimeType(),
                    'size_kb' => round($file->getSize() / 1024, 2),
                    'user_id' => auth()->id(),
                ]);
            }

            $this->files = [];

            // Dispatch success event
            $this->dispatch('documentsSaved');
        } catch (\Exception $e) {
            // Dispatch error event
            $this->dispatch('documentSaveError', message: $e->getMessage());
        }
    }

    /**
     * Get folder name based on model type
     */
    private function getFolderName()
    {
        // Extract model name from namespace
        $modelName = class_basename($this->modelType);

        // Convert to snake_case and pluralize
        $folderName = str($modelName)->snake()->plural();

        return "documents/{$folderName}/{$this->category}";
    }

    /**
     * Get uploaded files (for parent component to access)
     */
    public function getUploadedFiles()
    {
        return $this->files;
    }

    /**
     * Get total file count
     */
    public function getFileCount()
    {
        return count($this->files);
    }

    /**
     * Computed property for file count (for blade reactivity)
     */
    public function getFileCountProperty()
    {
        return count($this->files);
    }

    public function render()
    {
        return view('livewire.components.document-upload', [
            'fileCount' => count($this->files)
        ]);
    }
}
