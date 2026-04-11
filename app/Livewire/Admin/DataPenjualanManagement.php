<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataPenjualanImport;
use App\Exports\DataPenjualanExport;

#[Title('Data Penjualan')]
class DataPenjualanManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $file_import;
    public bool $showImportModal = false;

    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public $id_produk = '';
    public int $bulan = 1;
    public int $tahun;
    public string $jumlah = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public array $bulanOptions = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function mount(): void
    {
        $this->tahun = (int) now()->format('Y');
    }

    protected function rules(): array
    {
        return [
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'jumlah' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'bulan.required' => 'Bulan wajib dipilih.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer' => 'Tahun harus berupa angka.',
            'tahun.min' => 'Tahun minimal 2000.',
            'tahun.max' => 'Tahun maksimal 2100.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah tidak boleh negatif.',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $record = DataPenjualan::findOrFail($id);
        $this->editingId = $id;
        $this->id_produk = $record->id_produk ?? '';
        $this->bulan = $record->bulan;
        $this->tahun = $record->tahun;
        $this->jumlah = (string) $record->jumlah;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $record = DataPenjualan::findOrFail($this->editingId);
            $record->update($validated);
            session()->flash('success', 'Data penjualan berhasil diperbarui.');
        } else {
            DataPenjualan::create($validated);
            session()->flash('success', 'Data penjualan berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            DataPenjualan::destroy($this->deletingId);
            session()->flash('success', 'Data penjualan berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->id_produk = '';
        $this->bulan = 1;
        $this->tahun = (int) now()->format('Y');
        $this->jumlah = '';
        $this->editingId = null;
    }

    public function openImportModal(): void
    {
        $this->file_import = null;
        $this->resetErrorBag('file_import');
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->file_import = null;
        $this->resetErrorBag('file_import');
    }

    public function downloadTemplate()
    {
        return response()->streamDownload(function () {
            echo "nama_produk,tahun,bulan,jumlah\n";
            echo "Tahu,2023,1,150.50\n";
        }, 'template_import_penjualan.csv');
    }

    public function importData()
    {
        $this->validate([
            'file_import' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // max 5MB
        ], [
            'file_import.required' => 'Pilih file terlebih dahulu.',
            'file_import.file' => 'Upload harus berupa file.',
            'file_import.mimes' => 'Format file harus xlsx, xls, atau csv.',
            'file_import.max' => 'Ukuran file maksimal 5MB.'
        ]);

        try {
            Excel::import(new DataPenjualanImport, $this->file_import->path());
            session()->flash('success', 'Data penjualan berhasil diimpor.');
            $this->closeImportModal();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Error pada baris ' . $failures[0]->row() . ': ' . $failures[0]->errors()[0];
            $this->addError('file_import', $errorMsg);
        } catch (\Exception $e) {
            $this->addError('file_import', 'Gagal mengimpor data: Pastikan format template sudah benar.');
        }
    }

    public function exportData()
    {
        return Excel::download(new DataPenjualanExport, 'data_penjualan.xlsx');
    }

    public function render()
    {
        $records = DataPenjualan::query()
            ->with('produk')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('tahun', 'like', '%' . $this->search . '%')
            )
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        $produkOptions = Produk::all();

        return view('livewire.admin.data-penjualan-management', [
            'records' => $records,
            'produkOptions' => $produkOptions,
        ]);
    }
}
