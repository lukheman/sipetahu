<div class="sitahu-landing">

    <!-- ===== HERO SECTION ===== -->
    <section class="s-hero" id="beranda">
        <div class="hero-bg-img">
            {{-- Background image: ganti src dengan foto produk/tim pabrik tahu --}}
            <div class="hero-overlay"></div>
        </div>

        <nav-spacer style="display:block; height: var(--nav-height, 68px);"></nav-spacer>

        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-tag">
                    <span class="tag-dot"></span>
                    Inovasi UMKM Tahu &amp; Tempe
                </div>

                <h1 class="hero-headline">
                    Prediksi<br>
                    Penjualan<br>
                    <em class="hero-accent">Lebih Akurat.</em>
                </h1>

                <p class="hero-sub">
                    Bantu <strong>Pabrik Tahu Tempe Sumber Urip</strong> merencanakan produksi berdasarkan data historis. Bebas overproduction, lebih efisien.
                </p>

                <div class="hero-actions">
                    <a href="#prediksi" class="btn-hero-cta">
                        Mulai Prediksi
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="#metode" class="btn-hero-ghost">Pelajari Metode WMA</a>
                </div>

                <div class="hero-companies">
                    <span class="companies-label">Metode terpercaya dari</span>
                    <div class="companies-row">
                        <span class="company-chip">Weighted MA</span>
                        <span class="company-chip">MAD Analysis</span>
                        <span class="company-chip">MSE Evaluasi</span>
                        <span class="company-chip">Trend Forecast</span>
                    </div>
                </div>
            </div>

            <div class="hero-right">
                <div class="dashboard-card">
                    <div class="dc-header">
                        <div class="dc-dots">
                            <span></span><span></span><span></span>
                        </div>
                    </div>

                    <div class="dc-body">
                        <p class="dc-label">Tren Penjualan Tahu (kg)</p>
                        <div class="dc-stat">
                            <span class="dc-num">9,850</span>
                            <span class="dc-change positive">↑ 12%</span>
                        </div>

                        <div class="dc-chart">
                            <svg viewBox="0 0 340 120" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="fillGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#C8F135" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#C8F135" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <!-- Grid -->
                                <line x1="0" y1="30" x2="340" y2="30" stroke="rgba(255,255,255,0.07)" stroke-width="1"/>
                                <line x1="0" y1="60" x2="340" y2="60" stroke="rgba(255,255,255,0.07)" stroke-width="1"/>
                                <line x1="0" y1="90" x2="340" y2="90" stroke="rgba(255,255,255,0.07)" stroke-width="1"/>

                                <!-- Aktual line -->
                                <path d="M 0 100 C 40 90, 80 70, 120 75 C 160 80, 200 45, 240 38 C 270 32, 300 50, 340 42"
                                      fill="none" stroke="rgba(255,255,255,0.35)" stroke-width="2" stroke-linecap="round"/>

                                <!-- Area fill WMA -->
                                <path d="M 120 75 C 160 65, 200 40, 240 28 C 270 18, 310 25, 340 18 L 340 120 L 120 120 Z"
                                      fill="url(#fillGrad)"/>

                                <!-- WMA line -->
                                <path d="M 120 75 C 160 65, 200 40, 240 28 C 270 18, 310 25, 340 18"
                                      fill="none" stroke="#C8F135" stroke-width="2.5" stroke-linecap="round" stroke-dasharray="5,4"/>

                                <!-- Dots -->
                                <circle cx="120" cy="75" r="4" fill="#0d0d0d" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <circle cx="240" cy="28" r="4" fill="#0d0d0d" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <circle cx="340" cy="18" r="5" fill="#C8F135" stroke="#0d0d0d" stroke-width="2"/>
                            </svg>
                        </div>

                        <div class="dc-legend">
                            <span class="legend-item">
                                <span class="leg-line actual"></span> Data Aktual
                            </span>
                            <span class="legend-item">
                                <span class="leg-line wma"></span> Hasil WMA
                            </span>
                        </div>

                        <div class="dc-search">
                            <input type="text" placeholder="Input data penjualan (Kg)..." class="dc-input">
                            <button class="dc-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STATS BAR ===== -->
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-item">
                <span class="stat-num">WMA</span>
                <span class="stat-desc">Metode Prediksi</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">98%</span>
                <span class="stat-desc">Akurasi Rata-rata</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">MAD + MSE</span>
                <span class="stat-desc">Evaluasi Error</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-num">Real-time</span>
                <span class="stat-desc">Dashboard Interaktif</span>
            </div>
        </div>
    </div>

    <!-- ===== TENTANG SECTION ===== -->
    <section class="s-tentang" id="tentang">
        <div class="tentang-inner">
            <div class="tentang-left">
                <span class="sec-eyebrow">Tentang Sistem</span>
                <h2 class="sec-title">Solusi Cerdas<br>untuk UMKM Tahu</h2>
                <p class="sec-body">
                     dirancang khusus untuk <strong>Pabrik Tahu Tempe Sumber Urip</strong>. Dengan mengolah data historis penjualan menjadi prediksi masa depan yang akurat, kami membantu pemilik usaha tahu mengoptimalkan produksi dan menekan biaya.
                </p>
                <a href="{{ route('login') }}" class="btn-sec-cta">
                    Akses Sistem <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="tentang-right">
                <div class="about-card about-card--yellow">
                    <div class="about-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Prediksi Berbasis Data</h3>
                    <p>Mengonversi data historis menjadi prediksi penjualan akurat menggunakan model statistik WMA.</p>
                </div>
                <div class="about-card about-card--dark">
                    <div class="about-icon"><i class="fas fa-balance-scale-right"></i></div>
                    <h3>Kurangi Overproduction</h3>
                    <p>Cegah produksi berlebihan yang mengakibatkan pembusukan. Hemat biaya setiap bulannya.</p>
                </div>
                <div class="about-card about-card--outline">
                    <div class="about-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3>Keputusan Strategis</h3>
                    <p>Insight berharga untuk manajemen stok dan produksi yang lebih efisien dan terencana.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FITUR SECTION ===== -->
    <section class="s-fitur" id="fitur">
        <div class="fitur-inner">
            <div class="fitur-header">
                <span class="sec-eyebrow">Fitur Eksklusif</span>
                <h2 class="sec-title">Lengkap & Fleksibel<br>untuk Manajemen Anda</h2>
            </div>

            <div class="fitur-grid">
                <div class="fitur-card fitur-card--main">
                    <div class="fc-icon fc-icon--green">
                        <i class="fas fa-table"></i>
                    </div>
                    <h3>Input Data Penjualan</h3>
                    <p>Antarmuka sederhana untuk mencatat kuantitas penjualan harian atau bulanan dengan mudah dan cepat.</p>
                    <div class="fc-arrow">→</div>
                </div>
                <div class="fitur-card">
                    <div class="fc-icon fc-icon--yellow">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3>Perhitungan WMA Otomatis</h3>
                    <p>Model otomasi untuk menetapkan bobot ideal dan menghasilkan output prediksi terbaik tanpa kerumitan manual.</p>
                    <div class="fc-arrow">→</div>
                </div>
                <div class="fitur-card">
                    <div class="fc-icon fc-icon--blue">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Evaluasi Error (MAD & MSE)</h3>
                    <p>Transparansi kualitas prediksi dengan indikator Mean Absolute Deviation dan Mean Square Error.</p>
                    <div class="fc-arrow">→</div>
                </div>
                <div class="fitur-card fitur-card--cta">
                    <p class="fc-cta-text">Akses seluruh rekam jejak penjualan dalam satu platform terpusat.</p>
                    <a href="{{ route('login') }}" class="btn-fitur-cta">
                        Coba Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== METODE SECTION ===== -->
    <section class="s-metode" id="metode">
        <div class="metode-inner">
            <div class="metode-left">
                <span class="sec-eyebrow light">Algoritma Presisi</span>
                <h2 class="sec-title light">Metode Weighted<br>Moving Average</h2>
                <p class="sec-body light">
                    WMA memberikan bobot lebih besar pada data terbaru, sangat efektif untuk penjualan tahu yang fluktuatif mengikuti event atau musim tertentu.
                </p>

                <div class="formula-block">
                    <div class="formula-label">
                        <i class="fas fa-square-root-alt"></i> Rumus Formulasi
                    </div>
                    <div class="formula-eq">
                        WMA = (Σ Data × Bobot) ÷ Σ Bobot
                    </div>
                </div>
            </div>

            <div class="metode-right">
                <div class="weight-visual">
                    <div class="wv-title">Distribusi Bobot WMA</div>
                    <div class="wv-bars">
                        <div class="wv-bar-wrap">
                            <div class="wv-bar" style="height: 30%"><span>W1</span></div>
                            <div class="wv-bar-label">t-4</div>
                        </div>
                        <div class="wv-bar-wrap">
                            <div class="wv-bar" style="height: 50%"><span>W2</span></div>
                            <div class="wv-bar-label">t-3</div>
                        </div>
                        <div class="wv-bar-wrap">
                            <div class="wv-bar" style="height: 65%"><span>W3</span></div>
                            <div class="wv-bar-label">t-2</div>
                        </div>
                        <div class="wv-bar-wrap">
                            <div class="wv-bar" style="height: 85%"><span>W4</span></div>
                            <div class="wv-bar-label">t-1</div>
                        </div>
                        <div class="wv-bar-wrap">
                            <div class="wv-bar wv-bar--accent" style="height: 100%"><span>W5</span></div>
                            <div class="wv-bar-label">t (terbaru)</div>
                        </div>
                    </div>
                    <p class="wv-note">Semakin baru datanya, semakin besar bobotnya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== VISUALISASI / PREDIKSI SECTION ===== -->
    <section class="s-prediksi" id="prediksi">
        <div class="prediksi-inner">
            <div class="prediksi-header">
                <span class="sec-eyebrow">Demo Visual</span>
                <h2 class="sec-title">Visualisasi Data Riil</h2>
                <p class="sec-body center">Bandingkan data aktual dan hasil peramalan pada grafik interaktif berikut.</p>
            </div>

            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        Grafik Penjualan Aktual vs WMA
                    </div>
                    <div class="chart-badge">Live Preview</div>
                </div>

                <div class="chart-body">
                    <div class="chart-wrap">
                        <svg viewBox="0 0 800 280" xmlns="http://www.w3.org/2000/svg" class="chart-svg">
                            <defs>
                                <linearGradient id="actGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#94a3b8" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#94a3b8" stop-opacity="0"/>
                                </linearGradient>
                                <linearGradient id="wmaGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#C8F135" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#C8F135" stop-opacity="0"/>
                                </linearGradient>
                            </defs>

                            <!-- Grid lines -->
                            <line x1="60" y1="30" x2="780" y2="30" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="60" y1="90" x2="780" y2="90" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="60" y1="150" x2="780" y2="150" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>
                            <line x1="60" y1="210" x2="780" y2="210" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>

                            <!-- Y labels -->
                            <text x="50" y="34" text-anchor="end" font-size="10" fill="#94a3b8">12K</text>
                            <text x="50" y="94" text-anchor="end" font-size="10" fill="#94a3b8">9K</text>
                            <text x="50" y="154" text-anchor="end" font-size="10" fill="#94a3b8">6K</text>
                            <text x="50" y="214" text-anchor="end" font-size="10" fill="#94a3b8">3K</text>

                            <!-- X labels -->
                            <text x="110" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Jan</text>
                            <text x="210" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Feb</text>
                            <text x="310" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Mar</text>
                            <text x="410" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Apr</text>
                            <text x="510" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Mei</text>
                            <text x="610" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Jun</text>
                            <text x="710" y="260" text-anchor="middle" font-size="10" fill="#94a3b8">Jul</text>

                            <!-- Aktual area fill -->
                            <path d="M 110 190 L 210 145 L 310 170 L 410 100 L 510 115 L 610 60 L 710 82 L 710 240 L 110 240 Z"
                                  fill="url(#actGrad)"/>

                            <!-- Aktual path -->
                            <path d="M 110 190 L 210 145 L 310 170 L 410 100 L 510 115 L 610 60 L 710 82"
                                  fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                            <!-- WMA fill -->
                            <path d="M 310 170 L 410 130 L 510 100 L 610 65 L 710 45 L 710 240 L 310 240 Z"
                                  fill="url(#wmaGrad)"/>

                            <!-- WMA path -->
                            <path d="M 310 170 L 410 130 L 510 100 L 610 65 L 710 45"
                                  fill="none" stroke="#C8F135" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="6,4"/>

                            <!-- Actual dots -->
                            <circle cx="110" cy="190" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="210" cy="145" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="310" cy="170" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="410" cy="100" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="510" cy="115" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="610" cy="60" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="710" cy="82" r="4" fill="#fff" stroke="#94a3b8" stroke-width="2"/>

                            <!-- WMA dots -->
                            <circle cx="410" cy="130" r="4" fill="#0d0d0d" stroke="#C8F135" stroke-width="2"/>
                            <circle cx="510" cy="100" r="4" fill="#0d0d0d" stroke="#C8F135" stroke-width="2"/>
                            <circle cx="610" cy="65" r="4" fill="#0d0d0d" stroke="#C8F135" stroke-width="2"/>
                            <circle cx="710" cy="45" r="6" fill="#C8F135" stroke="#0d0d0d" stroke-width="2"/>

                            <!-- Tooltip at last WMA point -->
                            <rect x="630" y="20" width="100" height="36" rx="6" fill="#0d0d0d"/>
                            <text x="680" y="35" text-anchor="middle" font-size="9" fill="#94a3b8">Prediksi</text>
                            <text x="680" y="49" text-anchor="middle" font-size="11" fill="#C8F135" font-weight="bold">9,850 Kg</text>
                        </svg>
                    </div>

                    <div class="chart-legend">
                        <span class="cl-item">
                            <span class="cl-line cl-actual"></span>
                            Data Aktual
                        </span>
                        <span class="cl-item">
                            <span class="cl-line cl-wma"></span>
                            Hasil WMA
                        </span>
                        <span class="cl-item">
                            <span class="cl-dot cl-pred"></span>
                            Prediksi
                        </span>
                    </div>
                </div>
            </div>

            <div class="prediksi-cta">
                <a href="{{ route('login') }}" class="btn-prediksi-cta">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Sistem Prediksi
                </a>
            </div>
        </div>
    </section>

</div>

<x-slot:styles>
<style>
/* ===== LANDING PAGE COMPONENT STYLES ===== */

.sitahu-landing {
    --accent: #C8F135;
    --accent-dark: #a8cc1a;
    --ink: #0D0D0D;
    --chalk: #F5F5F0;
    --mist: #E8E8E2;
    --text-mid: #5a5a52;
    --text-faint: #9a9a8e;
    --green-brand: #10b981;
    --font-display: 'Syne', sans-serif;
    --font-body: 'DM Sans', sans-serif;
    --nav-height: 68px;
}

/* ===== SHARED ===== */
.sec-eyebrow {
    display: inline-block;
    font-family: var(--font-display);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--green-brand);
    background: rgba(16,185,129,0.1);
    padding: 0.3rem 0.9rem;
    border-radius: 20px;
    margin-bottom: 1rem;
}

.sec-eyebrow.light { color: var(--accent); background: rgba(200,241,53,0.15); }

.sec-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--ink);
    margin-bottom: 1rem;
}

.sec-title.light { color: #fff; }

.sec-body {
    font-size: 1rem;
    color: var(--text-mid);
    line-height: 1.75;
    max-width: 480px;
    margin-bottom: 2rem;
}

.sec-body.light { color: rgba(255,255,255,0.7); }
.sec-body.center { text-align: center; margin: 0 auto 2rem; }

/* ===== HERO ===== */
.s-hero {
    position: relative;
    min-height: 100vh;
    background: var(--ink);
    overflow: hidden;
}

.hero-bg-img {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(105deg, rgba(13,13,13,0.92) 0%, rgba(13,13,13,0.7) 50%, rgba(13,13,13,0.4) 100%),
        url('/images/bg-tahu.png');
    background-size: cover;
    background-position: center right;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 10% 80%, rgba(200,241,53,0.06) 0%, transparent 50%),
        radial-gradient(ellipse at 90% 20%, rgba(16,185,129,0.08) 0%, transparent 50%);
}

.hero-inner {
    position: relative;
    z-index: 2;
    max-width: 1320px;
    margin: 0 auto;
    padding: 5rem 2rem 4rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    min-height: calc(100vh - var(--nav-height));
}

.hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    font-weight: 500;
    color: rgba(255,255,255,0.7);
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    padding: 0.4rem 1rem;
    border-radius: 20px;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(8px);
}

.tag-dot {
    width: 7px; height: 7px;
    background: var(--accent);
    border-radius: 50%;
    animation: blink 2s infinite;
}

@keyframes blink {
    0%,100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.hero-headline {
    font-family: var(--font-display);
    font-size: clamp(3rem, 6vw, 5rem);
    font-weight: 800;
    line-height: 1.05;
    letter-spacing: -0.04em;
    color: #fff;
    margin-bottom: 1.5rem;
}

.hero-accent {
    font-style: normal;
    color: var(--accent);
    position: relative;
}

.hero-sub {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.7;
    max-width: 460px;
    margin-bottom: 2.5rem;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 3rem;
}

.btn-hero-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--accent);
    color: var(--ink);
    padding: 0.9rem 1.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-family: var(--font-display);
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
}

.btn-hero-cta:hover {
    background: var(--accent-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(200,241,53,0.25);
    color: var(--ink);
}

.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: rgba(255,255,255,0.8);
    padding: 0.9rem 1.75rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.95rem;
    text-decoration: none;
    border: 1.5px solid rgba(255,255,255,0.25);
    transition: all 0.2s ease;
}

.btn-hero-ghost:hover {
    border-color: rgba(255,255,255,0.6);
    color: #fff;
}

.hero-companies { }

.companies-label {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    display: block;
    margin-bottom: 0.75rem;
}

.companies-row { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.company-chip {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.6);
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 0.3rem 0.75rem;
    border-radius: 4px;
    font-weight: 500;
}

/* Dashboard Card */
.dashboard-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    overflow: hidden;
    backdrop-filter: blur(16px);
}

.dc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    background: rgba(255,255,255,0.03);
}

.dc-dots { display: flex; gap: 6px; }

.dc-dots span {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
}

.dc-dots span:nth-child(1) { background: #ff5f57; }
.dc-dots span:nth-child(2) { background: #febc2e; }
.dc-dots span:nth-child(3) { background: #28c840; }

.dc-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: var(--accent);
    background: rgba(200,241,53,0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
}

.pulse-dot {
    width: 6px; height: 6px;
    background: var(--accent);
    border-radius: 50%;
    animation: blink 1.5s infinite;
}

.dc-body { padding: 1.5rem; }

.dc-label { font-size: 0.82rem; color: rgba(255,255,255,0.45); margin-bottom: 0.5rem; }

.dc-stat {
    display: flex;
    align-items: baseline;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.dc-num {
    font-family: var(--font-display);
    font-size: 2.25rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.03em;
}

.dc-change {
    font-size: 0.9rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 4px;
}

.dc-change.positive {
    color: var(--accent);
    background: rgba(200,241,53,0.1);
}

.dc-chart { margin-bottom: 1rem; }
.dc-chart svg { width: 100%; display: block; }

.dc-legend {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.5);
}

.leg-line {
    display: inline-block;
    width: 20px;
    height: 2.5px;
    border-radius: 2px;
}

.leg-line.actual { background: rgba(255,255,255,0.35); }
.leg-line.wma { background: var(--accent); }

.dc-search {
    display: flex;
    gap: 0.5rem;
}

.dc-input {
    flex: 1;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px;
    padding: 0.65rem 1rem;
    color: #fff;
    font-size: 0.875rem;
    outline: none;
    font-family: var(--font-body);
    transition: border 0.2s;
}

.dc-input::placeholder { color: rgba(255,255,255,0.3); }
.dc-input:focus { border-color: var(--accent); }

.dc-search-btn {
    background: var(--accent);
    border: none;
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--ink);
    transition: background 0.2s;
}

.dc-search-btn:hover { background: var(--accent-dark); }

/* ===== STATS BAR ===== */
.stats-bar {
    background: var(--ink);
    border-top: 1px solid rgba(255,255,255,0.06);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 2rem 0;
}

.stats-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.stat-num {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--accent);
    letter-spacing: -0.02em;
}

.stat-desc {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.45);
    text-align: center;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: rgba(255,255,255,0.1);
}

/* ===== TENTANG ===== */
.s-tentang {
    padding: 6rem 0;
    background: var(--chalk);
}

.tentang-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
}

.btn-sec-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--ink);
    color: var(--chalk);
    padding: 0.875rem 1.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-family: var(--font-display);
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-sec-cta:hover {
    background: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    color: var(--chalk);
}

.tentang-right {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.about-card {
    padding: 1.5rem;
    border-radius: 12px;
    transition: transform 0.2s;
}

.about-card:hover { transform: translateX(4px); }

.about-card h3 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
    margin-top: 0.75rem;
}

.about-card p { font-size: 0.88rem; line-height: 1.6; }

.about-card--yellow {
    background: var(--accent);
    color: var(--ink);
}

.about-card--yellow .about-icon { color: var(--ink); }
.about-card--yellow h3 { color: var(--ink); }
.about-card--yellow p { color: rgba(13,13,13,0.7); }

.about-card--dark {
    background: var(--ink);
    color: #fff;
}

.about-card--dark .about-icon { color: var(--accent); }
.about-card--dark p { color: rgba(255,255,255,0.6); }

.about-card--outline {
    background: transparent;
    border: 1.5px solid var(--mist);
}

.about-card--outline .about-icon { color: var(--green-brand); }
.about-card--outline p { color: var(--text-mid); }

.about-icon { font-size: 1.25rem; }

/* ===== FITUR ===== */
.s-fitur {
    padding: 6rem 0;
    background: var(--mist);
}

.fitur-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 2rem;
}

.fitur-header {
    margin-bottom: 3rem;
}

.fitur-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.fitur-card {
    background: var(--chalk);
    border-radius: 12px;
    padding: 1.75rem;
    border: 1px solid var(--mist);
    transition: all 0.25s;
    position: relative;
    overflow: hidden;
}

.fitur-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.08);
}

.fitur-card--main {
    background: var(--ink);
    border-color: var(--ink);
}

.fitur-card--main h3 { color: #fff; }
.fitur-card--main p { color: rgba(255,255,255,0.55); }
.fitur-card--main .fc-arrow { color: rgba(255,255,255,0.3); }

.fitur-card--cta {
    background: var(--accent);
    border-color: var(--accent);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.fitur-card h3 {
    font-family: var(--font-display);
    font-size: 0.95rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    margin-top: 1rem;
    color: var(--ink);
}

.fitur-card p { font-size: 0.85rem; color: var(--text-mid); line-height: 1.6; }

.fc-icon {
    width: 42px; height: 42px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.fc-icon--green { background: rgba(16,185,129,0.1); color: var(--green-brand); }
.fc-icon--yellow { background: rgba(200,241,53,0.15); color: #7a8a00; }
.fc-icon--blue { background: rgba(14,165,233,0.1); color: #0ea5e9; }

.fc-arrow {
    position: absolute;
    bottom: 1.25rem;
    right: 1.25rem;
    font-size: 1.1rem;
    color: var(--text-faint);
}

.fc-cta-text {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.5;
    margin-bottom: 1.5rem;
}

.btn-fitur-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--ink);
    color: var(--chalk);
    padding: 0.7rem 1.25rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.875rem;
    font-family: var(--font-display);
    text-decoration: none;
    transition: all 0.2s;
}

.btn-fitur-cta:hover { background: #222; color: var(--chalk); }

/* ===== METODE ===== */
.s-metode {
    padding: 6rem 0;
    background: var(--ink);
}

.metode-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
}

.formula-block {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.formula-label {
    font-size: 0.82rem;
    color: var(--accent);
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.formula-eq {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    color: #fff;
    font-weight: 600;
    text-align: center;
    padding: 0.75rem 0;
    letter-spacing: 0.02em;
}

/* Weight visual */
.weight-visual {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 2rem;
}

.wv-title {
    font-family: var(--font-display);
    font-size: 0.9rem;
    font-weight: 700;
    color: rgba(255,255,255,0.7);
    margin-bottom: 2rem;
    text-align: center;
}

.wv-bars {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 0.75rem;
    height: 160px;
}

.wv-bar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.wv-bar {
    width: 100%;
    background: rgba(255,255,255,0.12);
    border-radius: 6px 6px 0 0;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 0.4rem;
    transition: background 0.3s;
}

.wv-bar span { font-size: 0.7rem; color: rgba(255,255,255,0.5); font-weight: 600; }

.wv-bar--accent { background: var(--accent) !important; }
.wv-bar--accent span { color: var(--ink); }

.wv-bar-label { font-size: 0.72rem; color: rgba(255,255,255,0.35); text-align: center; }

.wv-note {
    text-align: center;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.35);
    margin-top: 1.5rem;
}

/* ===== PREDIKSI ===== */
.s-prediksi {
    padding: 6rem 0;
    background: var(--chalk);
}

.prediksi-inner {
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 2rem;
}

.prediksi-header {
    text-align: center;
    margin-bottom: 3rem;
}

.chart-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--mist);
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}

.chart-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--mist);
    background: var(--chalk);
}

.chart-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-title i { color: var(--green-brand); }

.chart-badge {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--green-brand);
    background: rgba(16,185,129,0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

.chart-body { padding: 1.5rem 2rem 2rem; }

.chart-wrap { width: 100%; overflow: hidden; }

.chart-svg { width: 100%; display: block; }

.chart-legend {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
}

.cl-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: var(--text-mid);
}

.cl-line {
    display: inline-block;
    width: 24px;
    height: 3px;
    border-radius: 2px;
}

.cl-actual { background: #94a3b8; }
.cl-wma { background: #C8F135; }

.cl-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
}

.cl-pred { background: #C8F135; }

.prediksi-cta { text-align: center; margin-top: 3rem; }

.btn-prediksi-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--ink);
    color: var(--chalk);
    padding: 1rem 2.5rem;
    border-radius: 8px;
    font-weight: 700;
    font-family: var(--font-display);
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-prediksi-cta:hover {
    background: #1a1a1a;
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    color: var(--chalk);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hero-inner { grid-template-columns: 1fr; gap: 3rem; padding: 3rem 2rem; }
    .hero-right { max-width: 500px; }
    .tentang-inner { grid-template-columns: 1fr; gap: 3rem; }
    .metode-inner { grid-template-columns: 1fr; gap: 3rem; }
    .fitur-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
    .fitur-grid { grid-template-columns: 1fr; }
    .stats-inner { justify-content: center; }
    .stat-divider { display: none; }
    .hero-headline { font-size: 2.5rem; }
    .hero-actions { flex-direction: column; }
    .btn-hero-cta, .btn-hero-ghost { justify-content: center; }
}
</style>
</x-slot:styles>
