<div class="modern-card">
    <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">Hasil Prediksi WMA Bulanan (Tahu)</h5>

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
                @if ($nextPrediction && $records->onFirstPage())
                    <tr style="background-color: var(--hover-bg);">
                        <td class="fw-semibold text-primary">{{ $nextPrediction['tahun'] }}</td>
                        <td class="text-primary">{{ $bulanOptions[$nextPrediction['bulan']] ?? $nextPrediction['bulan'] }}</td>
                        <td class="text-muted fst-italic">Belum ada data</td>
                        <td>
                            <span class="badge bg-warning text-dark px-2 py-1 fs-6 shadow-sm">{{ number_format($nextPrediction['wma'], 2, ',', '.') }}</span>
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
                            {{ number_format($record->total_penjualan, 2, ',', '.') }}
                        </td>
                        <td>
                            @if($record->hasilPrediksi)
                                <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ number_format($record->hasilPrediksi->wma, 2, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->error, 2, ',', '.') : '-' }}</td>
                        <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mad, 2, ',', '.') : '-' }}</td>
                        <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mse, 2, ',', '.') : '-' }}</td>
                        <td>
                            @if($record->hasilPrediksi)
                                <span class="text-{{ $record->hasilPrediksi->mape < 20 ? 'success' : 'danger' }} fw-semibold">
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

    @if ($records->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $records->links() }}
        </div>
    @endif
</div>
