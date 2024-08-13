@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="left-side-register">
            <div class="register-box">
                <div class="d-flex flex-row mb-3">
                    <a href="/login" class="text-white">
                        <i class="bi bi-chevron-left me-3 fs-4"></i>
                    </a>
                    <span class="h2">Registrasi</span>
                </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <label for="name" class="form-label">Nama</label>
                    <div class="mb-3">
                        <input id="name" type="text"
                            class="form-control border border-secondary shadow @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            placeholder="Masukkan Nama">
                        @error('name')
                            <span class="invalid-feedback text-white" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label for="nik" class="form-label">Nomor Karyawan</label>
                    <div class="mb-3">
                        <input id="nik" type="text"
                        class="form-control border border-secondary shadow @error('nik') is-invalid @enderror"
                        name="nik" value="{{ old('nik') }}" required autocomplete="nik" autofocus
                        placeholder="Masukkan Nomor Karyawan">
                        @error('nik')
                        <span class="invalid-feedback text-white" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <label for="no_hp" class="form-label">Nomor Hp</label>
                    <div class="mb-3">
                        <input id="no_hp" type="text"
                            class="form-control border border-secondary shadow @error('no_hp') is-invalid @enderror"
                            name="no_hp" value="{{ old('no_hp') }}" required autocomplete="no_hp" autofocus
                            placeholder="Masukkan Nomor Hp">
                        @error('no_hp')
                            <span class="invalid-feedback text-white" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <label for="email" class="form-label">Email</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-envelope text-secondary"></i>
                        </span>
                        <input type="email" id="email" name="email"
                            class="form-control border-0 py-2 px-0 @error('email') is-invalid @enderror"
                            placeholder="Masukkan Email" value="{{ old('email') }}" aria-label="Masukkan Email"
                            aria-describedby="basic-addon2">
                        @error('email')
                            <span class="invalid-feedback text-white text-danger" role="alert">
                                <strong>{{ $message }}</strong>
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
                            placeholder="Masukkan Password" aria-label="Masukkan Password" aria-describedby="basic-addon2">
                        <span class="input-group-text bg-white border-0" id="basic-addon2">
                            <i class="bi bi-eye-slash text-secondary" id="togglePassword" style="cursor: pointer"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback text-white text-danger" role="alert">
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

                    {{-- level --}}
                    <div class="row mb-3 d-none">
                        <label for="level"
                            class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Level') }}</label>
                        <div class="col-md-6">
                            <input id="level" type="text" class="form-control" name="level" value=""
                                readonly>
                        </div>
                    </div>
                    <label for="level" class="form-label fw-bold d-flex justify-content-center">Silahkan Pilih
                        Role</label>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                                required onclick="setLevel('Admin Billper')" {{ old('btnradio') == 'Admin Billper' ? 'checked' : '' }}>
                            <label class="btn btn-outline-light" for="btnradio1">Admin Billper</label>

                            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                                required onclick="setLevel('AdminPra NPC')" {{ old('btnradio') == 'AdminPra NPC' ? 'checked' : '' }}>
                            <label class="btn btn-outline-light" for="btnradio2">Admin Pranpc</label>

                            <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                                required onclick="setLevel('User')" {{ old('btnradio') == 'User' ? 'checked' : '' }}>
                            <label class="btn btn-outline-light" for="btnradio3">Sales</label>
                        </div>
                    </div>


                    {{-- status --}}
                    <div class="row mb-3 d-none">
                        <label for="status"
                            class="col-md-4 col-form-label text-md-end fw-bold">{{ __('Status') }}</label>
                        <div class="col-md-6">
                            <input id="status" type="text" class="form-control" name="status"
                                value="Belum Aktif" readonly>
                        </div>
                    </div>

                    {{-- Button Register --}}
                    <button type="submit" class="btn-login mt-3">
                        <i class="bi bi-plus-circle"></i> Registrasi
                    </button>
                </form>
                <p class="register-link">Sudah punya akun? <a href="/home">Masuk</a>.</p>
            </div>
        </div>
        <div class="right-side"
            style="background-image: url('{{  asset('storage/file_assets/telkom-sbs.png') }}'); background-size: cover; background-position: center;">
            <div class="logo-container">
                <img src="{{  asset('storage/file_assets/logo-telkom.png') }}" alt="Logo 1" class="logo">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        // Role
        function setLevel(level) {
            document.getElementById('level').value = level;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnradio1').addEventListener('change', function() {
                if (this.checked) {
                    setLevel('Admin Billper');
                }
            });

            document.getElementById('btnradio2').addEventListener('change', function() {
                if (this.checked) {
                    setLevel('Admin Pranpc');
                }
            });

            document.getElementById('btnradio3').addEventListener('change', function() {
                if (this.checked) {
                    setLevel('Sales');
                }
            });
        });

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
