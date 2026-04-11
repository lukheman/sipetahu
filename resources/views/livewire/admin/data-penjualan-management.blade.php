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
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Data Penjualan</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari tahun..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Jumlah (kg)</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr wire:key="record-{{ $record->id_data_penjualan }}">
                            <td>
                                <span
                                    class="badge {{ optional($record->produk)->nama_produk == 'Tahu' ? 'bg-warning text-dark' : 'bg-success text-white' }} px-2 py-1">
                                    {{ optional($record->produk)->nama_produk ?? 'Pilih Produk' }}
                                </span>
                            </td>
                            <td class="fw-semibold" style="color: var(--text-primary);">
                                {{ $record->tahun }}
                            </td>
                            <td>
                                {{ $bulanOptions[$record->bulan] ?? $record->bulan }}
                            </td>
                            <td>
                                {{ number_format($record->jumlah, 2, ',', '.') }} kg
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $record->id_data_penjualan }})" title="Edit data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $record->id_data_penjualan }})" title="Hapus data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
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
                    <div class="mb-3">
                        <label for="id_produk" class="form-label">Produk <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-select @error('id_produk') is-invalid @enderror" id="id_produk"
                            wire:model="id_produk">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produkOptions as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                        @error('id_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" class="form-control @error('tahun') is-invalid @enderror" id="tahun"
                            wire:model="tahun" placeholder="Masukkan tahun" min="2000" max="2100">
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bulan" class="form-label">Bulan <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-select @error('bulan') is-invalid @enderror" id="bulan" wire:model="bulan">
                            @foreach ($bulanOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('bulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jumlah" class="form-label">Jumlah (kg) <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('jumlah') is-invalid @enderror"
                            id="jumlah" wire:model="jumlah" placeholder="Masukkan jumlah penjualan">
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
