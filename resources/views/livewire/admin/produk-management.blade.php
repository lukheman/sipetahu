<div>
    {{-- Page Header --}}
    <x-page-header title="Data Produk" subtitle="Kelola data produk (Tahu & Tempe)">
        <x-slot:actions>
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
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Semua Produk</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari produk..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nama Produk</th>
                        <th>Jenis Tahu</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr wire:key="record-{{ $record->id_produk }}">
                            <td class="text-muted">#{{ $record->id_produk }}</td>
                            <td class="fw-semibold" style="color: var(--text-primary);">
                                <span
                                    class="badge {{ $record->nama_produk == 'Tahu' ? 'bg-warning text-dark' : 'bg-success text-white' }} px-2 py-1">
                                    {{ $record->nama_produk }}
                                </span>
                            </td>
                            <td>
                                @if($record->jenis_tahu)
                                    <span class="badge bg-info">{{ ucwords($record->jenis_tahu) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($record->harga, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ \Illuminate\Support\Str::limit($record->deskripsi ?? '-', 50) }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $record->id_produk }})" title="Edit data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $record->id_produk }})" title="Hapus data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
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

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Data Produk' : 'Tambah Data Produk' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-select @error('nama_produk') is-invalid @enderror" id="nama_produk"
                            wire:model.live="nama_produk">
                            <option value="">-- Pilih Produk --</option>
                            <option value="Tahu">Tahu</option>
                            <option value="Tempe">Tempe</option>
                        </select>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($nama_produk === 'Tahu')
                    <div class="mb-3">
                        <label for="jenis_tahu" class="form-label">Jenis Tahu <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-select @error('jenis_tahu') is-invalid @enderror" id="jenis_tahu"
                            wire:model="jenis_tahu">
                            <option value="">-- Pilih Jenis Tahu --</option>
                            <option value="potongan besar">Potongan Besar</option>
                            <option value="potongan kecil">Potongan Kecil</option>
                        </select>
                        @error('jenis_tahu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga <span
                                style="color: var(--danger-color);">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                wire:model="harga" placeholder="Masukkan harga" min="0">
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                            wire:model="deskripsi" placeholder="Masukkan deskripsi opsional" rows="3"></textarea>
                        @error('deskripsi')
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
        message="Apakah Anda yakin ingin menghapus data produk ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus
        </x-slot:confirmButton>
    </x-confirm-modal>
</div>