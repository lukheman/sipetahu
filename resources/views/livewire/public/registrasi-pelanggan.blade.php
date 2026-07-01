<div class="login-container" style="max-width: 600px; margin: 0 auto;">
    <div class="login-card">
        <div class="text-center mb-5">
            <h2 style="font-family: var(--font-display); font-weight: 800; color: var(--ink);">Daftar Menjadi Pelanggan</h2>
            <p style="color: var(--text-mid); margin-top: 10px;">Silakan lengkapi data diri Anda di bawah ini untuk mendaftar sebagai pelanggan kami.</p>
        </div>

            <form wire:submit.prevent="submit">
                <div class="form-floating position-relative mb-3">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="nama_pelanggan" wire:model="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror" placeholder="Nama Lengkap" required>
                    <label for="nama_pelanggan">Nama Lengkap</label>
                    @error('nama_pelanggan') <div class="invalid-feedback" style="margin-left: 2.75rem;">{{ $message }}</div> @enderror
                </div>

                <div class="form-floating position-relative mb-3">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="text" id="no_hp" wire:model="no_hp" class="form-control @error('no_hp') is-invalid @enderror" placeholder="Nomor WhatsApp / HP" required>
                    <label for="no_hp">Nomor WhatsApp / HP</label>
                    @error('no_hp') <div class="invalid-feedback" style="margin-left: 2.75rem;">{{ $message }}</div> @enderror
                </div>

                <div class="form-floating position-relative mb-3">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="Alamat Email" required>
                    <label for="email">Alamat Email</label>
                    @error('email') <div class="invalid-feedback" style="margin-left: 2.75rem;">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                            <label for="password">Password</label>
                            @error('password') <div class="invalid-feedback" style="margin-left: 2.75rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                            <label for="password_confirmation">Konfirmasi Password</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating position-relative mb-4">
                    <i class="fas fa-map-marker-alt input-icon" style="top: 1.5rem; transform: none;"></i>
                    <textarea id="alamat" wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" style="height: 100px; padding-top: 1.5rem;" placeholder="Alamat Lengkap" required></textarea>
                    <label for="alamat">Alamat Lengkap</label>
                    @error('alamat') <div class="invalid-feedback" style="margin-left: 2.75rem;">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn-login" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">
                        <i class="fas fa-paper-plane me-2"></i> Daftar sebagai Pelanggan
                    </span>
                    <span wire:loading wire:target="submit">
                        <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                    </span>
                </button>
            </form>


        <div class="signup-link mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--green-brand); font-weight: 600; text-decoration: none;">Masuk di sini</a>
        </div>
        <div class="signup-link mt-3">
            <a href="/" style="color: var(--text-mid); font-weight:400; text-decoration: none;">
                <i class="fas fa-arrow-left" style="font-size:0.75rem;"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
