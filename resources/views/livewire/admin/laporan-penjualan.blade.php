<div>
    <x-page-header title="Laporan Penjualan" subtitle="Ringkasan penjualan tahu khusus untuk Pemilik. Anda dapat memfilter data berdasarkan tanggal.">
        <x-slot name="actions">
            <div class="d-flex gap-2">
                <x-button wire:click="exportPdf" wire:loading.attr="disabled" variant="danger">
                    <span wire:loading.remove wire:target="exportPdf">
                        <i class="fas fa-file-pdf me-2"></i>Cetak Laporan (PDF)
                    </span>
                    <span wire:loading wire:target="exportPdf">
                        <i class="fas fa-spinner fa-spin me-2"></i>Memproses...
                    </span>
                </x-button>
            </div>
        </x-slot>
    </x-page-header>

    <div>
        <!-- Filter Form -->
        <div class="modern-card mb-4">
            <form wire:submit.prevent="$refresh" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-muted fw-semibold">Tanggal Mulai</label>
                <input type="date" wire:model.defer="start_date" class="form-control" style="background-color: var(--bg-light); border-radius: 8px;">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted fw-semibold">Tanggal Akhir</label>
                <input type="date" wire:model.defer="end_date" class="form-control" style="background-color: var(--bg-light); border-radius: 8px;">
            </div>
            <div class="col-md-4">
                <x-button type="submit" class="btn btn-primary px-4 py-2 w-100 shadow-sm" style="border-radius: 8px;">
                    <i class="fas fa-filter me-2"></i>Filter Data
                </x-button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="modern-card text-center position-relative overflow-hidden" style="border-top: 4px solid var(--primary-color);">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-boxes fa-3x" style="color: var(--primary-color);"></i>
                </div>
                <h6 class="text-muted mb-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Produksi</h6>
                <h2 class="mb-2" style="color: var(--primary-color); font-weight: 800;">{{ number_format($summary['total_produksi'], 0, ',', '.') }}</h2>
                <div class="d-flex justify-content-center gap-3 text-muted" style="font-size: 0.85rem;">
                    <span><i class="fas fa-circle ms-1" style="color: var(--primary-light); font-size: 0.5rem; vertical-align: middle;"></i> Kecil: <strong>{{ number_format($summary['produksi_kecil'], 0, ',', '.') }}</strong></span>
                    <span><i class="fas fa-circle ms-1" style="color: var(--primary-dark); font-size: 0.5rem; vertical-align: middle;"></i> Besar: <strong>{{ number_format($summary['produksi_besar'], 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="modern-card text-center position-relative overflow-hidden" style="border-top: 4px solid var(--bs-success);">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-shopping-cart fa-3x text-success"></i>
                </div>
                <h6 class="text-muted mb-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Penjualan</h6>
                <h2 class="mb-2 text-success" style="font-weight: 800;">{{ number_format($summary['total_penjualan'], 0, ',', '.') }}</h2>
                <div class="d-flex justify-content-center gap-3 text-muted" style="font-size: 0.85rem;">
                    <span><i class="fas fa-circle ms-1 text-success opacity-50" style="font-size: 0.5rem; vertical-align: middle;"></i> Kecil: <strong>{{ number_format($summary['penjualan_kecil'], 0, ',', '.') }}</strong></span>
                    <span><i class="fas fa-circle ms-1 text-success" style="font-size: 0.5rem; vertical-align: middle;"></i> Besar: <strong>{{ number_format($summary['penjualan_besar'], 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="modern-card text-center position-relative overflow-hidden" style="border-top: 4px solid var(--bs-danger);">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-undo fa-3x text-danger"></i>
                </div>
                <h6 class="text-muted mb-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Total Tahu Kembali</h6>
                <h2 class="mb-2 text-danger" style="font-weight: 800;">{{ number_format($summary['kembali_kecil'] + $summary['kembali_besar'], 0, ',', '.') }}</h2>
                <div class="d-flex justify-content-center gap-3 text-muted" style="font-size: 0.85rem;">
                    <span><i class="fas fa-circle ms-1 text-danger opacity-50" style="font-size: 0.5rem; vertical-align: middle;"></i> Kecil: <strong>{{ number_format($summary['kembali_kecil'], 0, ',', '.') }}</strong></span>
                    <span><i class="fas fa-circle ms-1 text-danger" style="font-size: 0.5rem; vertical-align: middle;"></i> Besar: <strong>{{ number_format($summary['kembali_besar'], 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="modern-card">
        <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">Rincian Penjualan</h5>
        <div class="table-responsive">
            <table class="table table-modern align-middle text-center">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle border-end">Tanggal</th>
                        <th colspan="3" class="border-bottom border-end">Produksi Tahu</th>
                        <th colspan="3" class="border-bottom border-end">Penjualan Tahu</th>
                        <th colspan="2" class="border-bottom">Tahu Kembali</th>
                    </tr>
                    <tr>
                        <th>Kecil</th>
                        <th>Besar</th>
                        <th class="border-end">Total</th>
                        <th>Kecil</th>
                        <th>Besar</th>
                        <th class="border-end">Total</th>
                        <th>Kecil</th>
                        <th>Besar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="fw-semibold text-start border-end" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}</td>
                        <td>{{ number_format($record->produksi_tahu_kecil, 0, ',', '.') }}</td>
                        <td>{{ number_format($record->produksi_tahu_besar, 0, ',', '.') }}</td>
                        <td class="fw-bold border-end" style="color: var(--primary-color); background-color: rgba(67, 97, 238, 0.03);">{{ number_format($record->total_produksi, 0, ',', '.') }}</td>
                        <td>{{ number_format($record->penjualan_tahu_kecil, 0, ',', '.') }}</td>
                        <td>{{ number_format($record->penjualan_tahu_besar, 0, ',', '.') }}</td>
                        <td class="fw-bold text-success border-end" style="background-color: rgba(46, 204, 113, 0.03);">{{ number_format($record->total_penjualan, 0, ',', '.') }}</td>
                        <td class="text-danger">{{ number_format($record->tahu_kembali_kecil, 0, ',', '.') }}</td>
                        <td class="text-danger">{{ number_format($record->tahu_kembali_besar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-folder-open mb-3 fs-1 text-light"></i>
                            <p class="mb-0">Tidak ada data penjualan pada rentang tanggal ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
