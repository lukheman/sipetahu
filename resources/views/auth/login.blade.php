@component('layouts.guest', ['type' => 'auth'])
<div class="login-container">
    <div class="login-card">

        {{-- Brand Logo --}}
        <div class="brand-logo text-center mb-4">
            <img src="{{ asset('images/logo.jpg') }}" alt="Sipetahu Logo" style="width: 80px; height: 80px; border-radius: 16px; object-fit: cover; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <h1>Selamat Datang</h1>
            <p>Masuk ke sistem prediksi penjualan </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: var(--green-brand); border-radius: var(--radius-sm); padding: 12px 16px; font-size: 0.9rem;">
                <i class="fas fa-check-circle me-2" style="font-size: 1.1rem;"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="form-floating position-relative">
                <i class="fas fa-envelope input-icon"></i>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    placeholder="Alamat Email"
                    autofocus
                >
                <label for="email">Alamat Email</label>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-floating position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    placeholder="Password"
                >
                <label for="password">Password</label>
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            {{-- Submit --}}
            <button type="submit" class="btn-login">
                Masuk <i class="fas fa-arrow-right"></i>
            </button>

        </form>

        {{-- Divider --}}
        <div class="divider"><span>atau</span></div>

        {{-- Back to home & Register --}}
        <div class="signup-link mt-2">
            Belum punya akun pelanggan? <a href="{{ route('registrasi-pelanggan') }}">Daftar sekarang</a>
        </div>
        <div class="signup-link mt-3">
            <a href="/" style="color: var(--text-mid); font-weight:400;">
                <i class="fas fa-arrow-left" style="font-size:0.75rem;"></i>
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>

@slot('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endslot
@endcomponent
