<div>
    <div style="min-height: 100vh; padding-top: calc(var(--nav-height) + 40px); padding-bottom: 60px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div style="background: var(--bg-white); border-radius: var(--radius-lg); border: 1.5px solid var(--border-color); padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <div class="text-center mb-5">
                            <h2 style="font-family: var(--font-display); font-weight: 700; color: var(--text-primary);">Daftar Menjadi Pelanggan</h2>
                            <p style="color: var(--text-secondary); margin-top: 10px;">Silakan lengkapi data diri Anda di bawah ini untuk mendaftar sebagai pelanggan kami.</p>
                        </div>

                        @if ($successMessage)
                            <div class="alert alert-success d-flex align-items-center" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: var(--green-brand); border-radius: var(--radius-sm); padding: 15px;">
                                <i class="fas fa-check-circle me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    Pendaftaran berhasil! Terima kasih telah mendaftar sebagai pelanggan kami.
                                </div>
                            </div>
                        @else
                            <form wire:submit.prevent="submit">
                                <div class="mb-4">
                                    <label for="nama_pelanggan" style="display: block; font-weight: 500; margin-bottom: 8px; color: var(--text-primary);">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_pelanggan" wire:model="nama_pelanggan" class="form-control" style="background: var(--bg-light); border: 1.5px solid var(--border-color); color: var(--text-primary); border-radius: var(--radius-sm); padding: 12px 16px; width: 100%; transition: all var(--duration) var(--ease);" placeholder="Masukkan nama lengkap Anda" required>
                                    @error('nama_pelanggan') <span class="text-danger small mt-1" style="display: block;">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="no_hp" style="display: block; font-weight: 500; margin-bottom: 8px; color: var(--text-primary);">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                    <input type="text" id="no_hp" wire:model="no_hp" class="form-control" style="background: var(--bg-light); border: 1.5px solid var(--border-color); color: var(--text-primary); border-radius: var(--radius-sm); padding: 12px 16px; width: 100%; transition: all var(--duration) var(--ease);" placeholder="Contoh: 081234567890" required>
                                    @error('no_hp') <span class="text-danger small mt-1" style="display: block;">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-5">
                                    <label for="alamat" style="display: block; font-weight: 500; margin-bottom: 8px; color: var(--text-primary);">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea id="alamat" wire:model="alamat" class="form-control" style="background: var(--bg-light); border: 1.5px solid var(--border-color); color: var(--text-primary); border-radius: var(--radius-sm); padding: 12px 16px; width: 100%; min-height: 120px; transition: all var(--duration) var(--ease);" placeholder="Masukkan alamat lengkap Anda" required></textarea>
                                    @error('alamat') <span class="text-danger small mt-1" style="display: block;">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="btn-nav-primary" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem; border: none; cursor: pointer; position: relative; overflow: hidden;" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="submit">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran
                                    </span>
                                    <span wire:loading wire:target="submit">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                                    </span>
                                </button>
                            </form>
                        @endif

                        <div class="text-center mt-5">
                            <a href="/" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: color var(--duration) var(--ease);">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
