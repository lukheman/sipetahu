<div>
    {{-- Page Header --}}
    <x-page-header title="Dashboard Analisis" subtitle="Selamat Datang! Berikut adalah ringkasan data sistem saat ini.">
    </x-page-header>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <x-stat-card icon="fas fa-box" label="Total Produk" value="{{ $stats['total_produk'] }}"
                trend-value="Keseluruhan Jenis" trend-direction="up" variant="primary" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card icon="fas fa-table" label="Data Penjualan" value="{{ $stats['total_data'] }}" trend-value="Catatan Histori"
                trend-direction="up" variant="secondary" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card icon="fas fa-balance-scale" label="Volume Terjual" value="{{ number_format($stats['total_volume'], 0, ',', '.') }} Kg" trend-value="Total Keseluruhan"
                trend-direction="up" variant="success" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-stat-card icon="fas fa-percentage" label="Rata-rata MAPE" value="{{ number_format($stats['avg_mape'], 2, ',', '.') }}%"
                trend-value="Akurasi WMA (Tahu)" trend-direction="down" variant="warning" />
        </div>
    </div>

    {{-- WMA Forecast Chart --}}
    <div class="modern-card mb-4 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="m-0" style="color: var(--text-primary); font-weight: 600;"><i
                    class="fas fa-chart-area text-primary me-2"></i> Grafik Penjualan & Prediksi WMA (Tahu)</h5>
        </div>
        <div id="forecast_chart" style="min-height: 350px;"></div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const labels = @json($chartLabels);
            const actual = @json($chartActual);
            const wma = @json($chartWma);

            const options = {
                series: [{
                    name: 'Penjualan Aktual',
                    data: actual
                }, {
                    name: 'Prediksi WMA',
                    data: wma
                }],
                chart: {
                    type: 'line',
                    height: 380,
                    toolbar: { show: true },
                    fontFamily: 'Inter, sans-serif',
                    zoom: { enabled: false }
                },
                stroke: {
                    width: [3, 4],
                    curve: 'smooth',
                    dashArray: [0, 6] // Actual solid, Prediction dashed
                },
                colors: ['#4f46e5', '#f59e0b'],
                xaxis: {
                    categories: labels,
                    tooltip: { enabled: false }
                },
                yaxis: {
                    title: { text: 'Jumlah (Kg)', style: { fontWeight: 500 } }
                },
                markers: {
                    size: 5,
                    hover: { size: 7 }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    markers: { radius: 12 }
                },
                dataLabels: { enabled: false },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined" && y !== null) {
                                return y.toLocaleString('id-ID') + " kg";
                            }
                            return y;
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#forecast_chart"), options);
            chart.render();
        });
    </script>
</div>
