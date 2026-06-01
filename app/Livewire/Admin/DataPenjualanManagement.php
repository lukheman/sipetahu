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
    public string $tanggal = '';
    public string $jenis_pembeli = 'langsung';
    public ?int $id_distributor = null;
    public int $produksi_tahu_kecil = 0;
    public int $produksi_tahu_besar = 0;
    public int $total_produksi = 0;
    public int $penjualan_tahu_kecil = 0;
    public int $penjualan_tahu_besar = 0;
    public int $total_penjualan = 0;
    public int $tahu_kembali_kecil = 0;
    public int $tahu_kembali_besar = 0;

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
        $this->tanggal = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'jenis_pembeli' => ['required', 'in:distributor,langsung'],
            'id_distributor' => ['nullable', 'exists:distributor,id_distributor', 'required_if:jenis_pembeli,distributor'],
            'produksi_tahu_kecil' => ['required', 'integer', 'min:0'],
            'produksi_tahu_besar' => ['required', 'integer', 'min:0'],
            'total_produksi' => ['required', 'integer', 'min:0'],
            'penjualan_tahu_kecil' => ['required', 'integer', 'min:0'],
            'penjualan_tahu_besar' => ['required', 'integer', 'min:0'],
            'total_penjualan' => ['required', 'integer', 'min:0'],
            'tahu_kembali_kecil' => ['required', 'integer', 'min:0'],
            'tahu_kembali_besar' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            '*.required' => 'Kolom ini wajib diisi.',
            '*.integer' => 'Kolom ini harus berupa angka bulat.',
            '*.min' => 'Nilai tidak boleh negatif.',
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
        $this->tanggal = $record->tanggal;
        $this->jenis_pembeli = $record->jenis_pembeli;
        $this->id_distributor = $record->id_distributor;
        $this->produksi_tahu_kecil = $record->produksi_tahu_kecil;
        $this->produksi_tahu_besar = $record->produksi_tahu_besar;
        $this->total_produksi = $record->total_produksi;
        $this->penjualan_tahu_kecil = $record->penjualan_tahu_kecil;
        $this->penjualan_tahu_besar = $record->penjualan_tahu_besar;
        $this->total_penjualan = $record->total_penjualan;
        $this->tahu_kembali_kecil = $record->tahu_kembali_kecil;
        $this->tahu_kembali_besar = $record->tahu_kembali_besar;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($validated['jenis_pembeli'] === 'langsung') {
            $validated['id_distributor'] = null;
        }

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
        $this->tanggal = now()->format('Y-m-d');
        $this->jenis_pembeli = 'langsung';
        $this->id_distributor = null;
        $this->produksi_tahu_kecil = 0;
        $this->produksi_tahu_besar = 0;
        $this->total_produksi = 0;
        $this->penjualan_tahu_kecil = 0;
        $this->penjualan_tahu_besar = 0;
        $this->total_penjualan = 0;
        $this->tahu_kembali_kecil = 0;
        $this->tahu_kembali_besar = 0;
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
            echo "tanggal,produksi_tahu_kecil,produksi_tahu_besar,total_produksi,penjualan_tahu_kecil,penjualan_tahu_besar,total_penjualan,tahu_kembali_kecil,tahu_kembali_besar\n";
            echo "2025-01-01,7056,6912,13968,6624,5616,12240,432,1296\n";
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

    #[\Livewire\Attributes\Computed]
    public function monthlyRecords()
    {
        return DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
    }

    public function render()
    {
        $records = DataPenjualan::query()
            ->with('distributor')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('tanggal', 'like', '%' . $this->search . '%')
            )
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        $distributors = \App\Models\Distributor::orderBy('nama_distributor')->get();

        return view('livewire.admin.data-penjualan-management', [
            'records' => $records,
            'distributors' => $distributors,
        ]);
    }
}
