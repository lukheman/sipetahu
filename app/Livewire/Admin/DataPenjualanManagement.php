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

#[Title('Manajemen Penjualan')]
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

    public ?int $id_pelanggan = null;
    public array $penjualan_details = [];

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
        $this->penjualan_details = [
            ['id_produk' => '', 'jumlah' => 0]
        ];
    }

    public function addPenjualanDetail(): void
    {
        $this->penjualan_details[] = ['id_produk' => '', 'jumlah' => 0];
    }

    public function removePenjualanDetail($index): void
    {
        unset($this->penjualan_details[$index]);
        $this->penjualan_details = array_values($this->penjualan_details);
    }

    protected function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'id_pelanggan' => ['nullable', 'exists:pelanggan,id_pelanggan'],
            'penjualan_details.*.id_produk' => ['required', 'exists:produk,id_produk'],
            'penjualan_details.*.jumlah' => ['required', 'integer', 'min:0'],
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
        $record = DataPenjualan::with('detailPenjualans')->findOrFail($id);
        $this->editingId = $id;
        $this->tanggal = $record->tanggal;
        $this->id_pelanggan = $record->id_pelanggan;

        $this->penjualan_details = [];
        
        foreach ($record->detailPenjualans as $detail) {
            $this->penjualan_details[] = [
                'id_produk' => $detail->id_produk,
                'jumlah' => $detail->penjualan,
            ];
        }

        if (empty($this->penjualan_details)) $this->penjualan_details = [['id_produk' => '', 'jumlah' => 0]];

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->penjualan_details = array_values(array_filter($this->penjualan_details, function ($detail) {
            return !empty($detail['id_produk']);
        }));

        if (empty($this->penjualan_details)) {
            $this->addError('tanggal', 'Silakan pilih produk sebelum menyimpan data.');
            if (empty($this->penjualan_details)) $this->penjualan_details = [['id_produk' => '', 'jumlah' => 0]];
            return;
        }

        $validated = $this->validate();

        $total_penjualan = collect($validated['penjualan_details'] ?? [])->sum('jumlah');

        $merged_details = [];
        if (!empty($validated['penjualan_details'])) {
            foreach ($validated['penjualan_details'] as $p) {
                $pid = $p['id_produk'];
                if (!isset($merged_details[$pid])) $merged_details[$pid] = ['penjualan' => 0];
                $merged_details[$pid]['penjualan'] += $p['jumlah'];
            }
        }

        if ($this->editingId) {
            $record = DataPenjualan::findOrFail($this->editingId);
            $record->update([
                'tanggal' => $validated['tanggal'],
                'id_pelanggan' => $validated['id_pelanggan'],
                'total_penjualan' => $total_penjualan,
            ]);

            $record->detailPenjualans()->delete();
            foreach ($merged_details as $id_produk => $data) {
                $record->detailPenjualans()->create([
                    'id_produk' => $id_produk,
                    'penjualan' => $data['penjualan'],
                ]);
            }

            session()->flash('success', 'Data penjualan berhasil diperbarui.');
        } else {
            $record = DataPenjualan::create([
                'tanggal' => $validated['tanggal'],
                'id_pelanggan' => $validated['id_pelanggan'],
                'total_penjualan' => $total_penjualan,
            ]);

            foreach ($merged_details as $id_produk => $data) {
                $record->detailPenjualans()->create([
                    'id_produk' => $id_produk,
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

        $this->id_pelanggan = null;
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
            ->selectRaw('YEAR(data_penjualan.tanggal) as tahun, MONTH(data_penjualan.tanggal) as bulan, produk.nama_produk, SUM(detail_penjualan.penjualan) as total_penjualan, SUM(detail_penjualan.penjualan * produk.harga) as total_harga')
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
                'total_penjualan' => $d->total_penjualan,
                'total_harga' => $d->total_harga,
            ];
        }

        return collect($grouped)->values();
    }

    public function render()
    {
        $baseQuery = DataPenjualan::query()
            ->where('total_penjualan', '>', 0)
            ->when($this->search, fn($q) => $q->where('tanggal', 'like', '%' . $this->search . '%'));

        $records = (clone $baseQuery)
            ->with(['pelanggan', 'detailPenjualans.produk'])
            ->when(
                $this->filter_produk,
                fn($q) => 
                $q->whereHas('detailPenjualans', fn($dq) => $dq->where('id_produk', $this->filter_produk))
            )
            ->orderBy('tanggal', $this->sort_tanggal === 'asc' ? 'asc' : 'desc')
            ->paginate(10);

        if ($this->filter_produk) {
            $grandTotal = \App\Models\DetailPenjualan::whereHas('dataPenjualan', function($q) {
                    $q->where('total_penjualan', '>', 0)
                      ->when($this->search, fn($sq) => $sq->where('tanggal', 'like', '%' . $this->search . '%'));
                })
                ->where('id_produk', $this->filter_produk)
                ->sum('penjualan');
                
            $grandTotalHarga = \App\Models\DetailPenjualan::join('produk', 'detail_penjualan.id_produk', '=', 'produk.id_produk')
                ->whereHas('dataPenjualan', function($q) {
                    $q->where('total_penjualan', '>', 0)
                      ->when($this->search, fn($sq) => $sq->where('tanggal', 'like', '%' . $this->search . '%'));
                })
                ->where('detail_penjualan.id_produk', $this->filter_produk)
                ->sum(\Illuminate\Support\Facades\DB::raw('detail_penjualan.penjualan * produk.harga'));
        } else {
            $grandTotal = (clone $baseQuery)->sum('total_penjualan');
            
            $grandTotalHarga = \App\Models\DetailPenjualan::join('produk', 'detail_penjualan.id_produk', '=', 'produk.id_produk')
                ->join('data_penjualan', 'detail_penjualan.id_data_penjualan', '=', 'data_penjualan.id_data_penjualan')
                ->where('data_penjualan.total_penjualan', '>', 0)
                ->when($this->search, fn($sq) => $sq->where('data_penjualan.tanggal', 'like', '%' . $this->search . '%'))
                ->sum(\Illuminate\Support\Facades\DB::raw('detail_penjualan.penjualan * produk.harga'));
        }

        $pelanggans = \App\Models\Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.admin.data-penjualan-management', [
            'records' => $records,
            'pelanggans' => $pelanggans,
            'grandTotal' => $grandTotal,
            'grandTotalHarga' => $grandTotalHarga,
        ]);
    }
}
