<?php

namespace App\Livewire\Admin;

use App\Models\DataProduksi;
use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataProduksiImport;
use App\Exports\DataProduksiExport;

#[Title('Manajemen Produksi')]
class DataProduksiManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $file_import;
    public bool $showImportModal = false;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'p')]
    public string $filter_produk = '';

    #[Url(as: 's')]
    public string $sort_tanggal = 'desc';

    // Form fields
    public string $tanggal = '';

    public array $produksi_details = [];

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
        $this->initializeDetails();
    }

    protected function initializeDetails(): void
    {
        $this->produksi_details = [
            ['id_produk' => '', 'jumlah' => 0]
        ];
    }

    public function addProduksiDetail(): void
    {
        $this->produksi_details[] = ['id_produk' => '', 'jumlah' => 0];
    }

    public function removeProduksiDetail($index): void
    {
        unset($this->produksi_details[$index]);
        $this->produksi_details = array_values($this->produksi_details);
    }

    protected function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'produksi_details.*.id_produk' => ['required', 'exists:produk,id_produk'],
            'produksi_details.*.jumlah' => ['required', 'integer', 'min:0'],
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterProduk(): void
    {
        $this->resetPage();
    }

    public function updatedSortTanggal(): void
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
        $this->resetForm();
        $record = DataProduksi::with('detailProduksis')->findOrFail($id);
        $this->editingId = $id;
        $this->tanggal = $record->tanggal;

        $this->produksi_details = [];
        
        foreach ($record->detailProduksis as $detail) {
            $this->produksi_details[] = [
                'id_produk' => $detail->id_produk,
                'jumlah' => $detail->produksi,
            ];
        }

        if (empty($this->produksi_details)) $this->produksi_details = [['id_produk' => '', 'jumlah' => 0]];

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->produksi_details = array_values(array_filter($this->produksi_details, function ($detail) {
            return !empty($detail['id_produk']);
        }));

        if (empty($this->produksi_details)) {
            $this->addError('tanggal', 'Silakan pilih produk sebelum menyimpan data.');
            if (empty($this->produksi_details)) $this->produksi_details = [['id_produk' => '', 'jumlah' => 0]];
            return;
        }

        $validated = $this->validate();

        $total_produksi = collect($validated['produksi_details'] ?? [])->sum('jumlah');

        $merged_details = [];
        if (!empty($validated['produksi_details'])) {
            foreach ($validated['produksi_details'] as $p) {
                $pid = $p['id_produk'];
                if (!isset($merged_details[$pid])) $merged_details[$pid] = ['produksi' => 0];
                $merged_details[$pid]['produksi'] += $p['jumlah'];
            }
        }

        if ($this->editingId) {
            $record = DataProduksi::findOrFail($this->editingId);
            $record->update([
                'tanggal' => $validated['tanggal'],
                'total_produksi' => $total_produksi,
            ]);

            $record->detailProduksis()->delete();
            foreach ($merged_details as $id_produk => $data) {
                $record->detailProduksis()->create([
                    'id_produk' => $id_produk,
                    'produksi' => $data['produksi'],
                ]);
            }

            session()->flash('success', 'Data produksi berhasil diperbarui.');
        } else {
            $record = DataProduksi::create([
                'tanggal' => $validated['tanggal'],
                'total_produksi' => $total_produksi,
            ]);

            foreach ($merged_details as $id_produk => $data) {
                $record->detailProduksis()->create([
                    'id_produk' => $id_produk,
                    'produksi' => $data['produksi'],
                ]);
            }

            session()->flash('success', 'Data produksi berhasil ditambahkan.');
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
            DataProduksi::destroy($this->deletingId);
            session()->flash('success', 'Data produksi berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public bool $showDeleteAllModal = false;

    public function confirmDeleteAll(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\DetailProduksi::truncate();
        DataProduksi::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        session()->flash('success', 'Semua data produksi berhasil dihapus.');
        $this->showDeleteAllModal = false;
        $this->resetPage();
    }

    public function cancelDeleteAll(): void
    {
        $this->showDeleteAllModal = false;
    }

    protected function resetForm(): void
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->initializeDetails();
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
        return Excel::download(new \App\Exports\TemplateProduksiExport, 'template_import_produksi.xlsx');
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
            $import = new DataProduksiImport;
            Excel::import($import, $this->file_import->path());

            if ($import->importedCount > 0) {
                session()->flash('success', "Berhasil mengimpor {$import->importedCount} data produksi.");
            } else {
                session()->flash('error', 'Tidak ada data yang berhasil diimpor. Pastikan format file sesuai template.');
            }
            $this->closeImportModal();
        } catch (\Exception $e) {
            $this->addError('file_import', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        return Excel::download(new DataProduksiExport($this->search, $this->filter_produk, $this->sort_tanggal), 'data_produksi.xlsx');
    }

    #[\Livewire\Attributes\Computed]
    public function products()
    {
        return Produk::orderBy('nama_produk')->get();
    }

    #[\Livewire\Attributes\Computed]
    public function monthlyRecords()
    {
        $details = \App\Models\DetailProduksi::join('data_produksi', 'detail_produksi.id_data_produksi', '=', 'data_produksi.id_data_produksi')
            ->join('produk', 'detail_produksi.id_produk', '=', 'produk.id_produk')
            ->selectRaw('YEAR(data_produksi.tanggal) as tahun, MONTH(data_produksi.tanggal) as bulan, produk.nama_produk, SUM(detail_produksi.produksi) as total_produksi')
            ->groupBy('tahun', 'bulan', 'produk.nama_produk')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('produk.nama_produk', 'asc')
            ->get();

        $grouped = [];
        foreach($details as $d) {
            $key = $d->tahun . '-' . str_pad($d->bulan, 2, '0', STR_PAD_LEFT);
            if(!isset($grouped[$key])) {
                $grouped[$key] = [
                    'tahun' => $d->tahun,
                    'bulan' => $d->bulan,
                    'produk' => []
                ];
            }
            $grouped[$key]['produk'][] = [
                'nama_produk' => $d->nama_produk,
                'total_produksi' => $d->total_produksi,
            ];
        }

        return collect($grouped)->values();
    }

    public function render()
    {
        $records = DataProduksi::query()
            ->where('total_produksi', '>', 0)
            ->with(['detailProduksis.produk'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('tanggal', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filter_produk,
                fn($q) => 
                $q->whereHas('detailProduksis', fn($dq) => $dq->where('id_produk', $this->filter_produk))
            )
            ->orderBy('tanggal', $this->sort_tanggal === 'asc' ? 'asc' : 'desc')
            ->paginate(10);

        return view('livewire.admin.data-produksi-management', [
            'records' => $records,
        ]);
    }
}
