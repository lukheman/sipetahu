<div class="modern-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Hasil Prediksi WMA Harian (Tahu)</h5>
        <div class="d-flex gap-2">
            <div style="width: 150px;">
                <select wire:model.live="perPage" class="form-select form-select-sm shadow-sm" style="border-radius: 8px; background-color: var(--bg-light);">
                    <option value="10">10 Data</option>
                    <option value="25">25 Data</option>
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="0">Semua Data</option>
                </select>
            </div>
            <div style="width: 200px;">
                <select wire:model.live="sort_tanggal" class="form-select form-select-sm shadow-sm" style="border-radius: 8px; background-color: var(--bg-light);">
                    <option value="desc">Tanggal Terbaru</option>
                    <option value="asc">Tanggal Terlama</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Aktual (Xt)</th>
                    <th>Tahu Besar</th>
                    <th>Tahu Kecil</th>
                    <th>Prediksi (WMA)</th>
                    <th>Error</th>
                    <th>MAD</th>
                    <th>MSE</th>
                    <th>MAPE (%)</th>
                </tr>
            </thead>
            <tbody>
                @if ($nextPrediction && $records->onFirstPage() && $sort_tanggal === 'desc')
                    <tr style="background-color: var(--hover-bg);">
                        <td class="fw-semibold text-primary">{{ $nextPrediction['tanggal'] }}</td>
                        <td class="text-muted fst-italic">Belum ada data</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td>
                            <span class="badge bg-warning text-dark px-2 py-1 fs-6 shadow-sm">{{ number_format($nextPrediction['wma'], 2, ',', '.') }}</span>
                            @if(!empty($nextPrediction['detail_wma']))
                                <div class="mt-1" style="font-size: 0.75rem; color: var(--text-muted);">
                                    Rumus: {{ $nextPrediction['detail_wma'] }}
                                </div>
                            @endif
                        </td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    </tr>
                @endif
                @forelse ($records as $record)
                    <tr>
                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($record->tanggal)->translatedFormat('d F Y') }}</td>
                        <td class="fw-bold" style="color: var(--primary-color);">
                            {{ number_format($record->total_penjualan, 2, ',', '.') }}
                        </td>
                        <td>{{ number_format($record->tahu_besar, 0, ',', '.') }}</td>
                        <td>{{ number_format($record->tahu_kecil, 0, ',', '.') }}</td>
                        <td>
                            @if($record->hasilPrediksi)
                                <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ number_format($record->hasilPrediksi->wma, 2, ',', '.') }}</span>
                                @if($record->detail_wma)
                                    <div class="mt-1" style="font-size: 0.75rem; color: var(--text-muted);">
                                        Rumus: {{ $record->detail_wma }}
                                    </div>
                                @endif
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
                        <td colspan="9" class="text-center py-4 text-muted">Belum ada data penjualan Tahu.</td>
                    </tr>
                @endforelse
                @if ($nextPrediction && $records->onLastPage() && $sort_tanggal === 'asc')
                    <tr style="background-color: var(--hover-bg);">
                        <td class="fw-semibold text-primary">{{ $nextPrediction['tanggal'] }}</td>
                        <td class="text-muted fst-italic">Belum ada data</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td>
                            <span class="badge bg-warning text-dark px-2 py-1 fs-6 shadow-sm">{{ number_format($nextPrediction['wma'], 2, ',', '.') }}</span>
                            @if(!empty($nextPrediction['detail_wma']))
                                <div class="mt-1" style="font-size: 0.75rem; color: var(--text-muted);">
                                    Rumus: {{ $nextPrediction['detail_wma'] }}
                                </div>
                            @endif
                        </td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($records->hasPages())
        <div class="d-flex justify-content-end mt-4">
            {{ $records->links() }}
        </div>
    @endif
</div>
