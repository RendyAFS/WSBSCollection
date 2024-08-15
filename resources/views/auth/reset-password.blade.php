@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="left-side">
            <div class="login-box">
                <div class="mb-4 w-100 text-center contain-head">
                    <div class="logo-left-side text-center">
                        <img src="{{ asset('storage/file_assets/logo-telkom2.png') }}" alt="Logo 1" class="logo">
                    </div>
                    <span class="h3">
                        Reset Password
                    </span>
                </div>

                <!-- Formulir Reset Password -->
                <form method="POST" action="{{ route('updatePassword') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <label for="password" class="form-label">Password Baru</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-control border-0 py-2 px-0 @error('password') is-invalid @enderror"
                            placeholder="Masukkan Password" aria-label="Masukkan Password" aria-describedby="basic-addon2">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-eye-slash text-secondary" id="togglePassword" style="cursor: pointer"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback text-white " role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-lock text-secondary"></i>
                        </span>
                        <input id="password-confirm" type="password" class="form-control border-0 py-2 px-0"
                            name="password_confirmation" required autocomplete="new-password"
                            aria-describedby="basic-addon2" placeholder="Konfirmasi Password">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-eye-slash text-secondary" id="togglePassword2" style="cursor: pointer"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn-login mt-3">
                        <i class="bi bi-box-arrow-in-right"></i> Submit
                    </button>
                </form>
            </div>
        </div>
        <div class="right-side"
            style="background-image: url('{{ asset('storage/file_assets/telkom-sbs.png') }}'); background-size: cover; background-position: center;">
            <div class="logo-container">
                <img src="{{ asset('storage/file_assets/logo-telkom.png') }}" alt="Logo 1" class="logo">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Password Reset',
                    text: "{{ session('status') }}",
                    confirmButtonColor: '#831a16',
                    willClose: () => {
                        // Redirect after alert closes
                        window.location.href = "{{ session('redirect') }}";
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#831a16'
                });
            @endif
        });

        // Toggle show password
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const togglePassword2 = document.getElementById('togglePassword2');
            const passwordConfirm = document.getElementById('password-confirm');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the icon
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            togglePassword2.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);

                // Toggle the icon
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>
@endpush
