<div>
    <x-page-header title="Prediksi Penjualan WMA (Tahu)" subtitle="Analisis dan prediksi tren penjualan menggunakan metode Weighted Moving Average.">
        <x-slot name="actions">
            <div class="d-flex gap-2">
                @if (auth()->user()->role === \App\Enums\Role::ADMIN)
                    <button wire:click="kalkulasiWMA" class="btn btn-warning shadow-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="kalkulasiWMA">
                            <i class="fas fa-calculator me-2"></i>Kalkulasi WMA
                        </span>
                        <span wire:loading wire:target="kalkulasiWMA">
                            <i class="fas fa-spinner fa-spin me-2"></i>Menghitung...
                        </span>
                    </button>
                @endif
            </div>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success" title="Sukses!" class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

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

    {{-- Kesimpulan Prediksi untuk Pengguna Biasa --}}
    @if(isset($nextPrediction))
        <div class="modern-card mb-4 border-0 text-white"
            style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
            <h5 class="mb-3 text-white"><i class="fas fa-lightbulb text-warning me-2"></i> Kesimpulan Prediksi</h5>
            <p class="mb-2 fs-5">
                Berdasarkan tren penjualan Tahu di bulan-bulan sebelumnya, kami memperkirakan jumlah penjualan untuk
                <strong>{{ $bulanOptions[$nextPrediction['bulan']] ?? $nextPrediction['bulan'] }} {{ $nextPrediction['tahun'] }}</strong> adalah sebanyak
                <strong>{{ number_format($nextPrediction['wma'], 0, ',', '.') }}</strong>.
            </p>
            <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                Tingkat akurasi ditunjukkan oleh persen MAPE di atas (saat ini {{ number_format($avgMAPE, 2) }}%). Semakin
                kecil nilainya, semakin akurat perhitungannya.
            </p>
        </div>
    @endif

    <livewire:admin.prediksi-tahu-table :nextPrediction="$nextPrediction" />
</div>