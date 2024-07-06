@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="left-side">
            <div class="login-box">
                <div class="d-flex flex-row mb-3">
                    <a href="/" class="text-white">
                        <i class="bi bi-chevron-left me-3 fs-4"></i>
                    </a>
                    <span class="h2">Login</span>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-envelope text-secondary"></i>
                        </span>
                        <input type="email" id="email" name="email"
                            class="form-control border-0 py-2 px-0 @error('email') is-invalid @enderror"
                            placeholder="Masukkan Email" aria-label="Masukkan Email" aria-describedby="basic-addon2"
                            required>
                        @error('email')
                            <span class="invalid-feedback text-danger"role="alert">
                                <strong>Maaf, Perikasa Kembali Email</strong>
                            </span>
                        @enderror
                    </div>
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-control border-0 py-2 px-0 @error('password') is-invalid @enderror"
                            placeholder="Masukkan Password" aria-label="Masukkan Password" aria-describedby="basic-addon2"
                            required>
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-eye-slash text-secondary" id="togglePassword" style="cursor: pointer"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>Maaf, Perikasa Kembali Password</strong>
                            </span>
                        @enderror

                    </div>
                    <button type="submit" class="btn-login mt-3">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </form>
                <p class="register-link">Belum punya akun? <a href="/register" class="fw-bold">Registrasi</a>.</p>
            </div>
        </div>
        <div class="right-side"
            style="background-image: url('{{ Vite::asset('resources/images/telkom-sbs.png') }}'); background-size: cover; background-position: center;">
            <div class="logo-container">
                <img src="{{ Vite::asset('resources/images/logo-telkom.png') }}" alt="Logo 1" class="logo">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the icon
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
@endpush
