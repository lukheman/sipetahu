<div>
    <x-page-header title="Manajemen Pelanggan" subtitle="Kelola data pelanggan untuk transaksi penjualan.">
        <x-slot name="actions">
            <x-button data-bs-toggle="modal" data-bs-target="#pelangganModal" wire:click="resetInputFields">
                <i class="fas fa-plus me-2"></i>Tambah Pelanggan
            </x-button>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    <div class="modern-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="m-0" style="color: var(--text-primary); font-weight: 600;">Daftar Pelanggan</h5>
            <div class="search-box" style="max-width: 300px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" placeholder="Cari nama..." wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-modern align-middle text-center">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th class="text-start">Nama Pelanggan</th>
                        <th>No HP</th>
                        <th class="text-start">Alamat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggans as $pelanggan)
                        <tr>
                            <td>{{ $loop->iteration + $pelanggans->firstItem() - 1 }}</td>
                            <td class="text-start fw-semibold">{{ $pelanggan->nama_pelanggan }}</td>
                            <td>{{ $pelanggan->no_hp ?: '-' }}</td>
                            <td class="text-start">{{ $pelanggan->alamat ?: '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <x-button
                                            wire:click="edit({{ $pelanggan->id_pelanggan }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#pelangganModal"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </x-button>
                                    <x-button variant="danger"
                                            wire:click="delete({{ $pelanggan->id_pelanggan }})"
                                            wire:confirm="Yakin ingin menghapus pelanggan ini?"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open mb-3 fs-1 text-light"></i>
                                <p class="mb-0">Tidak ada data pelanggan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pelanggans->hasPages())
            <div class="mt-4">
                {{ $pelanggans->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form -->
    <div wire:ignore.self class="modal fade" id="pelangganModal" tabindex="-1" aria-labelledby="pelangganModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header bg-light border-bottom-0 rounded-top-4 pb-0">
                    <h5 class="modal-title fw-bold" id="pelangganModalLabel" style="color: var(--text-primary);">
                        {{ $isEditMode ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-3">
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pelanggan</label>
                            <input type="text" class="form-control form-control-lg bg-light @error('nama_pelanggan') is-invalid @enderror" wire:model="nama_pelanggan" placeholder="Masukkan nama pelanggan">
                            @error('nama_pelanggan') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" class="form-control form-control-lg bg-light @error('no_hp') is-invalid @enderror" wire:model="no_hp" placeholder="Contoh: 08123456789">
                            @error('no_hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control form-control-lg bg-light @error('alamat') is-invalid @enderror" wire:model="alamat" placeholder="Alamat lengkap pelanggan" rows="3"></textarea>
                            @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2">
                        <x-button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-button>
                            <x-button type="submit" >
                                {{ $isEditMode ? 'Perbarui' : 'Simpan' }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('close-modal', () => {
        let modalEl = document.getElementById('pelangganModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    });
</script>
@endscript
