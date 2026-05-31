<div>
    <x-page-header title="Laporan Prediksi WMA" subtitle="Ringkasan analisis prediksi menggunakan Weighted Moving Average khusus untuk Pemilik.">
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

    {{-- Statistik Card --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: var(--primary-color);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h6 class="text-muted mb-1">Rata-rata MAD</h6>
                <h3 class="mb-0">{{ number_format($avgMAD, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h6 class="text-muted mb-1">Rata-rata MSE</h6>
                <h3 class="mb-0">{{ number_format($avgMSE, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                    <i class="fas fa-percentage"></i>
                </div>
                <h6 class="text-muted mb-1">Rata-rata MAPE</h6>
                <h3 class="mb-0">{{ number_format($avgMAPE, 2) }}%</h3>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <livewire:admin.prediksi-tahu-table />
</div>
