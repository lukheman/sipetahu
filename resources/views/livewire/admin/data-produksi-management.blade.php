<div>
    {{-- Page Header --}}
    <x-page-header title="Data Produksi" subtitle="Kelola data produksi tahu">
        <x-slot:actions>
            <x-button variant="danger" icon="fas fa-trash-alt" wire:click="confirmDeleteAll" title="Hapus Semua Data" class="me-auto">
                Hapus Semua
            </x-button>
            <x-button variant="success" icon="fas fa-file-excel" wire:click="exportData" title="Export ke Excel">
                Export
            </x-button>
            <x-button variant="warning" icon="fas fa-upload" wire:click="openImportModal" title="Import dari Excel">
                Import
            </x-button>
            <x-button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Data
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger" title="Error!" class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Data Table Card --}}
    <div class="modern-card">
        <x-tabs variant="underline" class="mb-4">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab" aria-controls="harian" aria-selected="true">Data Produksi Harian</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bulanan-tab" data-bs-toggle="tab" data-bs-target="#bulanan" type="button" role="tab" aria-controls="bulanan" aria-selected="false">Rekap Bulanan</button>
            </li>
            <x-slot:content>
                <div class="tab-pane fade show active" id="harian" role="tabpanel" aria-labelledby="harian-tab">
                    {{-- Search and Filters --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Data Produksi</h5>
            <div class="d-flex flex-column flex-md-row gap-2">
                <div style="min-width: 150px;">
                    <x-select
                        wire:model.live="filter_produk"
                        :options="$this->products->pluck('nama_produk', 'id_produk')->toArray()"
                        placeholder="Semua Produk"
                    />
                </div>

                <div style="min-width: 150px;">
                    <x-select
                        wire:model.live="sort_tanggal"
                        :options="['desc' => 'Terbaru', 'asc' => 'Terlama']"
                        placeholder="Urutan"
                    />
                </div>

                <div style="max-width: 300px;">
                    <x-input
                        type="text"
                        placeholder="Cari tahun..."
                        icon="fas fa-search"
                        wire:model.live.debounce.300ms="search"
                    />
                </div>
            </div>
        </div>

        {{-- Table --}}
        <x-table>
            <x-slot:head>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Produksi</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </x-slot:head>
                <tbody>
                    @forelse ($records as $record)
                        @php
                            $details = $record->detailPenjualans;
                            if ($filter_produk) {
                                $details = $details->where('id_produk', $filter_produk)->values();
                            }
                            $detailsCount = max(1, $details->count());
                        @endphp

                        @if ($details->isEmpty())
                            <tr wire:key="record-{{ $record->id_data_penjualan }}">
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}
                                </td>
                                <td class="align-middle text-muted">-</td>
                                <td class="align-middle text-muted">-</td>
                                <td class="align-middle">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <x-button
                                            wire:click="openEditModal({{ $record->id_data_penjualan }})" title="Edit data">
                                            <i class="fas fa-edit"></i>
                                        </x-button>
                                        <x-button variant="danger"
                                            wire:click="confirmDelete({{ $record->id_data_penjualan }})" title="Hapus data">
                                            <i class="fas fa-trash-alt"></i>
                                        </x-button>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($details as $index => $detail)
                                <tr wire:key="record-{{ $record->id_data_penjualan }}-{{ $index }}">
                                    @if ($index === 0)
                                        <td rowspan="{{ $detailsCount }}" class="align-middle border-end">
                                            {{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}
                                        </td>
                                    @endif

                                    <td class="align-middle">{{ $detail->produk->nama_produk ?? '-' }}</td>
                                    <td class="align-middle border-end">{{ number_format($detail->produksi, 0, ',', '.') }}</td>

                                    @if ($index === 0)
                                        <td rowspan="{{ $detailsCount }}" class="align-middle">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <x-btn-edit
                                                    wire:click="openEditModal({{ $record->id_data_penjualan }})"
                                                    tooltip="Edit data"
                                                />
                                                <x-btn-delete
                                                    wire:click="confirmDelete({{ $record->id_data_penjualan }})"
                                                    tooltip="Hapus data"
                                                />
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <x-empty-state
                                    size="sm"
                                    icon="fas fa-box-open"
                                    title="Tidak ada data ditemukan"
                                    description="Belum ada data penjualan yang cocok dengan kriteria pencarian Anda."
                                />
                            </td>
                        </tr>
                    @endforelse
        </x-table>

        {{-- Pagination --}}
        @if ($records->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $records->links() }}
            </div>
        @endif
                </div>

                <div class="tab-pane fade" id="bulanan" role="tabpanel" aria-labelledby="bulanan-tab">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th class="text-start">Bulan</th>
                                    <th>Nama Produk</th>
                                    <th>Total Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->monthlyRecords as $rec)
                                    @php $detailsCount = max(1, count($rec['produk'])); @endphp
                                    @foreach($rec['produk'] as $index => $prod)
                                        <tr>
                                            @if($index === 0)
                                                <td class="text-start fw-semibold align-middle border-end" rowspan="{{ $detailsCount }}">
                                                    {{ $bulanOptions[$rec['bulan']] ?? $rec['bulan'] }} {{ $rec['tahun'] }}
                                                </td>
                                            @endif
                                            <td class="align-middle">{{ $prod['nama_produk'] }}</td>
                                            <td class="fw-bold align-middle" style="color: var(--primary-color);">{{ number_format($prod['total_produksi'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data bulanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-slot:content>
        </x-tabs>
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        @if($editingId)
                            Edit Data Produksi
                        @else
                            Tambah Data Produksi
                        @endif
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                        @if ($errors->any())
                            <x-alert variant="danger" title="Terdapat Kesalahan" class="mb-4">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-alert>
                        @endif


                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-input type="date" label="Tanggal" wire:model="tanggal" required :error="$errors->first('tanggal')" />
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header text-white py-2" style="background-color: var(--primary-color);">
                                    <h6 class="mb-0"><i class="fas fa-industry me-2"></i>Detail Produksi</h6>
                                </div>
                                <div class="card-body p-3">
                                    @foreach($produksi_details as $index => $detail)
                                        <div class="row align-items-center">
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <x-select
                                                    label=""
                                                    wire:model="produksi_details.{{ $index }}.id_produk"
                                                    :options="$this->products->pluck('nama_produk', 'id_produk')->toArray()"
                                                    placeholder="-- Pilih Produk --"
                                                />
                                            </div>
                                            <div class="col-md-6">
                                                <x-input type="number" label="" wire:model="produksi_details.{{ $index }}.jumlah" placeholder="Jumlah Produksi" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <x-button type="button" variant="outline" wire:click="closeModal">Batal</x-button>
                        <x-button type="submit" variant="primary">Simpan Data</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus data produksi ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-confirm-modal>

    {{-- Delete All Confirmation Modal --}}
    <x-confirm-modal :show="$showDeleteAllModal" title="Peringatan Keras: Hapus Semua Data!"
        message="PERHATIAN! Anda akan menghapus SELURUH data produksi. Data yang sudah dihapus tidak dapat dikembalikan. Lanjutkan?"
        on-confirm="deleteAll" on-cancel="cancelDeleteAll" variant="danger" icon="fas fa-radiation">
        <x-slot:confirmButton>
            <i class="fas fa-skull-crossbones me-2"></i>Ya, Hapus Semua
        </x-slot:confirmButton>
    </x-confirm-modal>

    {{-- Import Modal --}}
    @if ($showImportModal)
        <div class="modal-backdrop-custom" wire:click.self="closeImportModal">
            <div class="modal-content-custom" style="max-width: 500px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">Import Data Produksi</h5>
                    <button type="button" class="modal-close-btn" wire:click="closeImportModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="importData">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">File Excel/CSV <span
                                    style="color: var(--danger-color);">*</span></label>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none"
                                wire:click="downloadTemplate">
                                <i class="fas fa-download me-1"></i> Download Template
                            </button>
                        </div>

                        <x-file-upload
                            id="file_import"
                            wire:model="file_import"
                            accept=".xlsx,.xls,.csv"
                            maxSize="5MB"
                            :error="$errors->first('file_import')"
                            hint="Format file: .xlsx atau .csv. Baris pertama (header) akan diabaikan. Pastikan format sesuai template."
                        />

                        <div wire:loading wire:target="file_import" class="text-muted small mt-1">
                            <i class="fas fa-spinner fa-spin me-1"></i> Mengunggah file...
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <x-button type="button" variant="outline" wire:click="closeImportModal">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="primary" wire:loading.attr="disabled"
                            wire:target="importData, file_import">
                            <span wire:loading.remove wire:target="importData"><i class="fas fa-upload me-2"></i>Import
                                Data</span>
                            <span wire:loading wire:target="importData"><i
                                    class="fas fa-spinner fa-spin me-2"></i>Mengimpor...</span>
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
