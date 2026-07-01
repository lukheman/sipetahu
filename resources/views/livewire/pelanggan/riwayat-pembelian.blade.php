<div>
    <x-page-header title="Riwayat Pembelian" subtitle="Daftar transaksi yang pernah Anda lakukan." />
    
    <div class="modern-card mt-4">
        <div class="table-responsive">
            <table class="table table-modern align-middle text-center">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th class="text-start">Tanggal</th>
                        <th>Total Pembelian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualans as $penjualan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}</td>
                            <td>
                                <strong>{{ $penjualan->detailPenjualan->sum('jumlah') }}</strong> item
                                <div class="text-muted" style="font-size: 0.8em;">(Rp {{ number_format($penjualan->detailPenjualan->sum(function($d) { return $d->jumlah * $d->produk->harga; }), 0, ',', '.') }})</div>
                            </td>
                            <td>
                                <span class="badge-modern text-bg-success"><i class="fas fa-check me-1"></i>Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fas fa-box-open mb-3 fs-1 text-light"></i>
                                <p class="mb-0">Belum ada riwayat pembelian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
