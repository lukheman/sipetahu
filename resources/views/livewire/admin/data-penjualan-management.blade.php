<div>
    {{-- Page Header --}}
    <x-page-header title="Data Penjualan" subtitle="Kelola data penjualan">
        <x-slot:actions>
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
                <button class="nav-link active" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab" aria-controls="harian" aria-selected="true">Data Harian</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bulanan-tab" data-bs-toggle="tab" data-bs-target="#bulanan" type="button" role="tab" aria-controls="bulanan" aria-selected="false">Rekap Bulanan</button>
            </li>
            <x-slot:content>
                <div class="tab-pane fade show active" id="harian" role="tabpanel" aria-labelledby="harian-tab">
                    {{-- Search and Filters --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Data Penjualan</h5>
            <div class="d-flex flex-column flex-md-row gap-2">
                <select class="form-select" wire:model.live="filter_produk" style="min-width: 150px; border-radius: 8px;">
                    <option value="">Semua Produk</option>
                    @foreach($this->products as $product)
                        <option value="{{ $product->id_produk }}">{{ $product->nama_produk }}</option>
                    @endforeach
                </select>

                <select class="form-select" wire:model.live="sort_tanggal" style="min-width: 150px; border-radius: 8px;">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>

                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color); border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search" style="color: var(--text-muted);"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Cari tahun..."
                        wire:model.live.debounce.300ms="search" style="border-left: none; border-radius: 0 8px 8px 0;">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Nama Produk</th>
                        <th>Produksi</th>
                        <th>Penjualan</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
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
                                <td class="align-middle">
                                    @if($record->jenis_pembeli === 'distributor')
                                        <span class="badge bg-primary">Distributor: {{ $record->distributor?->nama_distributor }}</span>
                                    @else
                                        <span class="badge bg-secondary">Langsung</span>
                                    @endif
                                </td>
                                <td class="align-middle text-muted">-</td>
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
                                        <td rowspan="{{ $detailsCount }}" class="align-middle border-end">
                                            @if($record->jenis_pembeli === 'distributor')
                                                <span class="badge bg-primary">Distributor: {{ $record->distributor?->nama_distributor }}</span>
                                            @else
                                                <span class="badge bg-secondary">Langsung</span>
                                            @endif
                                        </td>
                                    @endif
                                    
                                    <td class="align-middle">{{ $detail->produk->nama_produk ?? '-' }}</td>
                                    <td class="align-middle">{{ number_format($detail->produksi, 0, ',', '.') }}</td>
                                    <td class="align-middle border-end">{{ number_format($detail->penjualan, 0, ',', '.') }}</td>
                                    
                                    @if ($index === 0)
                                        <td rowspan="{{ $detailsCount }}" class="align-middle">
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
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-box-open mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Tidak ada data ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
                                    <th>Bulan</th>
                                    <th>Jumlah Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->monthlyRecords as $rec)
                                    <tr>
                                        <td class="fw-semibold">{{ $bulanOptions[$rec->bulan] ?? $rec->bulan }} {{ $rec->tahun }}</td>
                                        <td class="fw-bold" style="color: var(--primary-color);">{{ number_format($rec->total_penjualan, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">Belum ada data bulanan.</td>
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
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Data Penjualan' : 'Tambah Data Penjualan' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Jenis Pembeli <span style="color: var(--danger-color);">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_pembeli" id="pembeli_langsung" value="langsung" wire:model.live="jenis_pembeli">
                                    <label class="form-check-label" for="pembeli_langsung">Datang Langsung (Pembeli Biasa)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_pembeli" id="pembeli_distributor" value="distributor" wire:model.live="jenis_pembeli">
                                    <label class="form-check-label" for="pembeli_distributor">Distributor</label>
                                </div>
                            </div>
                            @error('jenis_pembeli') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        @if($jenis_pembeli === 'distributor')
                            <div class="col-md-12 mb-3">
                                <x-select 
                                    label="Pilih Distributor" 
                                    wire:model="id_distributor" 
                                    :options="$distributors->pluck('nama_distributor', 'id_distributor')->toArray()" 
                                    placeholder="-- Pilih Distributor --"
                                    required
                                />
                            </div>
                        @endif

                        <div class="col-md-12 mb-3">
                            <x-input type="date" label="Tanggal" wire:model="tanggal" required />
                        </div>

                        <div class="col-md-12 mb-3">
                            <h6 class="border-bottom pb-2">Data Produksi & Penjualan Per Produk</h6>
                        </div>

                        @foreach($this->products as $product)
                            <div class="col-12 mb-2"><strong style="color: var(--primary-color);">{{ $product->nama_produk }}</strong></div>
                            <div class="col-md-6 mb-3">
                                <x-input type="number" label="Produksi" wire:model="details.{{ $product->id_produk }}.produksi" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input type="number" label="Penjualan" wire:model="details.{{ $product->id_produk }}.penjualan" required />
                            </div>
                        @endforeach

                        <div class="col-md-12 mt-3 mb-2">
                            <h6 class="border-bottom pb-2">Total (Keseluruhan)</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input type="number" label="Total Produksi" wire:model="total_produksi" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input type="number" label="Total Penjualan" wire:model="total_penjualan" required />
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <x-button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-button>
                        <x-button type="submit" variant="primary">
                            {{ $editingId ? 'Simpan Perubahan' : 'Simpan Data' }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus data penjualan ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-confirm-modal>

    {{-- Import Modal --}}
    @if ($showImportModal)
        <div class="modal-backdrop-custom" wire:click.self="closeImportModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 500px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">Import Data Penjualan</h5>
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

                        <input type="file" class="form-control @error('file_import') is-invalid @enderror" id="file_import"
                            wire:model="file_import" accept=".xlsx,.xls,.csv" required>

                        <div wire:loading wire:target="file_import" class="text-muted small mt-1">
                            <i class="fas fa-spinner fa-spin me-1"></i> Mengunggah file...
                        </div>

                        @error('file_import')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Format file: .xlsx atau .csv. Baris pertama (header) akan diabaikan. Pastikan format sesuai
                                template.
                            </div>
                        @enderror
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
