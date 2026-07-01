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

    #[Url(as: 'p')]
    public string $filter_produk = '';

    #[Url(as: 's')]
    public string $sort_tanggal = 'desc';

    // Form fields
    public string $tanggal = '';
    public string $jenis_pembeli = 'langsung';
    public ?int $id_distributor = null;
    public array $details = [];

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
        $products = Produk::all();
        $this->details = [];
        foreach ($products as $product) {
            $this->details[$product->id_produk] = [
                'produksi' => 0,
                'penjualan' => 0,
            ];
        }
    }

    protected function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'jenis_pembeli' => ['required', 'in:pelanggan,langsung'],
            'id_pelanggan' => ['nullable', 'exists:pelanggan,id_pelanggan', 'required_if:jenis_pembeli,pelanggan'],
            'total_produksi' => ['required', 'integer', 'min:0'],
            'total_penjualan' => ['required', 'integer', 'min:0'],
            'details.*.produksi' => ['required', 'integer', 'min:0'],
            'details.*.penjualan' => ['required', 'integer', 'min:0'],
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
        $record = DataPenjualan::with('detailPenjualans')->findOrFail($id);
        $this->editingId = $id;
        $this->tanggal = $record->tanggal;
        $this->jenis_pembeli = $record->jenis_pembeli;
        $this->id_pelanggan = $record->id_pelanggan;
        $this->total_produksi = $record->total_produksi;
        $this->total_penjualan = $record->total_penjualan;

        foreach ($record->detailPenjualans as $detail) {
            $this->details[$detail->id_produk] = [
                'produksi' => $detail->produksi,
                'penjualan' => $detail->penjualan,
            ];
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($validated['jenis_pembeli'] === 'langsung') {
            $validated['id_pelanggan'] = null;
        }

        if ($this->editingId) {
            $record = DataPenjualan::findOrFail($this->editingId);
            $record->update([
                'tanggal' => $validated['tanggal'],
                'jenis_pembeli' => $validated['jenis_pembeli'],
                'id_pelanggan' => $validated['id_pelanggan'],
                'total_produksi' => $validated['total_produksi'],
                'total_penjualan' => $validated['total_penjualan'],
            ]);

            foreach ($validated['details'] as $id_produk => $data) {
                $record->detailPenjualans()->updateOrCreate(
                    ['id_produk' => $id_produk],
                    [
                        'produksi' => $data['produksi'],
                        'penjualan' => $data['penjualan'],
                    ]
                );
            }

            session()->flash('success', 'Data penjualan berhasil diperbarui.');
        } else {
            $record = DataPenjualan::create([
                'tanggal' => $validated['tanggal'],
                'jenis_pembeli' => $validated['jenis_pembeli'],
                'id_pelanggan' => $validated['id_pelanggan'],
                'total_produksi' => $validated['total_produksi'],
                'total_penjualan' => $validated['total_penjualan'],
            ]);

            foreach ($validated['details'] as $id_produk => $data) {
                $record->detailPenjualans()->create([
                    'id_produk' => $id_produk,
                    'produksi' => $data['produksi'],
                    'penjualan' => $data['penjualan'],
                ]);
            }

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

    public bool $showDeleteAllModal = false;

    public function confirmDeleteAll(): void
    {
        $this->showDeleteAllModal = true;
    }

    public function deleteAll(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\HasilPrediksi::truncate();
        \App\Models\DetailPenjualan::truncate();
        DataPenjualan::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        session()->flash('success', 'Semua data penjualan berhasil dihapus beserta seluruh riwayat prediksinya.');
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
        $this->jenis_pembeli = 'langsung';
        $this->id_distributor = null;
        $this->total_produksi = 0;
        $this->total_penjualan = 0;
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
        return Excel::download(new \App\Exports\TemplatePenjualanExport, 'template_import_penjualan.xlsx');
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
            $import = new DataPenjualanImport;
            Excel::import($import, $this->file_import->path());

            if ($import->importedCount > 0) {
                session()->flash('success', "Berhasil mengimpor {$import->importedCount} data penjualan.");
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
        return Excel::download(new DataPenjualanExport($this->search, $this->filter_produk, $this->sort_tanggal), 'data_penjualan.xlsx');
    }

    #[\Livewire\Attributes\Computed]
    public function products()
    {
        return Produk::orderBy('nama_produk')->get();
    }

    #[\Livewire\Attributes\Computed]
    public function monthlyRecords()
    {
        $details = \App\Models\DetailPenjualan::join('data_penjualan', 'detail_penjualan.id_data_penjualan', '=', 'data_penjualan.id_data_penjualan')
            ->join('produk', 'detail_penjualan.id_produk', '=', 'produk.id_produk')
            ->selectRaw('YEAR(data_penjualan.tanggal) as tahun, MONTH(data_penjualan.tanggal) as bulan, produk.nama_produk, SUM(detail_penjualan.penjualan) as total_penjualan_produk')
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
                    'total_penjualan' => 0,
                    'produk' => []
                ];
            }
            $grouped[$key]['total_penjualan'] += $d->total_penjualan_produk;
            $grouped[$key]['produk'][$d->nama_produk] = $d->total_penjualan_produk;
        }

        return collect($grouped)->values();
    }

    public function render()
    {
        $records = DataPenjualan::query()
            ->with(['pelanggan', 'detailPenjualans.produk'])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('tanggal', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filter_produk,
                fn($q) => 
                $q->whereHas('detailPenjualans', fn($dq) => $dq->where('id_produk', $this->filter_produk))
            )
            ->orderBy('tanggal', $this->sort_tanggal === 'asc' ? 'asc' : 'desc')
            ->paginate(10);

        $pelanggans = \App\Models\Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.admin.data-penjualan-management', [
            'records' => $records,
            'pelanggans' => $pelanggans,
        ]);
    }
}
