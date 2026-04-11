<div>
    <x-page-header title="Prediksi WMA (Tahu)"
        subtitle="Perhitungan Weighted Moving Average khusus untuk data penjualan Tahu">
        <x-slot:actions>
            @if(auth()->user()->role === 'admin')
                <x-button variant="warning" icon="fas fa-calculator" wire:click="kalkulasiWMA" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="kalkulasiWMA">Kalkulasi WMA</span>
                    <span wire:loading wire:target="kalkulasiWMA">Menghitung...</span>
                </x-button>
            @endif
        </x-slot:actions>
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
                <strong>{{ $bulanOptions[$nextPrediction['bulan']] ?? $nextPrediction['bulan'] }}
                    {{ $nextPrediction['tahun'] }}</strong> adalah sebanyak
                <strong>{{ number_format($nextPrediction['wma'], 0, ',', '.') }} kg</strong>.
            </p>
            <p class="mb-0 text-white-50" style="font-size: 0.9rem;">
                Tingkat akurasi ditunjukkan oleh persen MAPE di atas (saat ini {{ number_format($avgMAPE, 2) }}%). Semakin
                kecil nilainya, semakin akurat perhitungannya.
            </p>
        </div>
    @endif

    {{-- Data Table --}}
    <div class="modern-card">
        <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">Hasil Prediksi WMA (Tahu)</h5>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th>Aktual (Xt)</th>
                        <th>Prediksi (WMA)</th>
                        <th>Error</th>
                        <th>MAD</th>
                        <th>MSE</th>
                        <th>MAPE (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($nextPrediction && $records->currentPage() == 1)
                        <tr style="background-color: var(--hover-bg);">
                            <td class="fw-semibold text-primary">{{ $nextPrediction['tahun'] }}</td>
                            <td class="text-primary">
                                {{ $bulanOptions[$nextPrediction['bulan']] ?? $nextPrediction['bulan'] }}
                            </td>
                            <td class="text-muted fst-italic">Belum ada data</td>
                            <td>
                                <span
                                    class="badge bg-warning text-dark px-2 py-1 fs-6 shadow-sm">{{ number_format($nextPrediction['wma'], 2, ',', '.') }}</span>
                            </td>
                            <td class="text-muted">-</td>
                            <td class="text-muted">-</td>
                            <td class="text-muted">-</td>
                            <td class="text-muted">-</td>
                        </tr>
                    @endif
                    @forelse ($records as $record)
                        <tr>
                            <td class="fw-semibold">{{ $record->tahun }}</td>
                            <td>{{ $bulanOptions[$record->bulan] ?? $record->bulan }}</td>
                            <td class="fw-bold" style="color: var(--primary-color);">
                                {{ number_format($record->jumlah, 2, ',', '.') }}
                            </td>
                            <td>
                                @if($record->hasilPrediksi)
                                    <span
                                        class="badge bg-warning text-dark px-2 py-1 fs-6">{{ number_format($record->hasilPrediksi->wma, 2, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->error, 2, ',', '.') : '-' }}
                            </td>
                            <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mad, 2, ',', '.') : '-' }}
                            </td>
                            <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mse, 2, ',', '.') : '-' }}
                            </td>
                            <td>
                                @if($record->hasilPrediksi)
                                    <span
                                        class="text-{{ $record->hasilPrediksi->mape < 20 ? 'success' : 'danger' }} fw-semibold">
                                        {{ number_format($record->hasilPrediksi->mape, 2, ',', '.') }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data penjualan Tahu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>