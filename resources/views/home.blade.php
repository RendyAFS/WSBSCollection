@extends('layouts.app')

@section('content')
    <div class="home-bg">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-md-5 mt-5 pt-md-0 pt-5">
                    <div class="{{ Auth::user()->email_verified_at ? 'd-block' : 'd-none' }}">
                        <h1 class="home-h1 mb-4">Informasi Akun</h1>
                    </div>
                    <div class="{{ Auth::user()->email_verified_at ? 'd-none' : 'd-block' }}">
                        <h1 class="home-h1 mb-4">Validasi Akun</h1>
                    </div>
                    <div class="card">
                        <div class="card-header fs-5 fw-bold" id="calc-stunting">Informasi</div>
                        <div class="d-flex justify-content-center mt-3 mb-4">
                            <img src="{{ asset('storage/file_assets/logo-telkom.png') }}" alt=""
                                id="home-logo-telkom">
                        </div>
                        <div class="card-body">
                            <div class="{{ Auth::user()->email_verified_at ? 'd-block' : 'd-none' }}">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                Akun <b>{{ Auth::user()->name }}</b> sudah Terdaftar dengan Status akun
                                @if (Auth::user()->status === 'Aktif' && Auth::user()->email_verified_at !== null)
                                    <b class="text-success">{{ Auth::user()->status }}</b>!
                                @else
                                    <b class="text-danger">{{ Auth::user()->status }}</b>!
                                @endif
                                Silahkan tunggu <strong>Aktivasi</strong> dari Super Admin
                            </div>

                            <div class="{{ Auth::user()->email_verified_at ? 'd-none' : 'd-block' }}">
                                <form action="{{ route('verifyOtp') }}" method="POST">
                                    @csrf

                                    <div class="{{ Auth::user()->email_verified_at ? 'd-none' : 'd-block' }}">
                                        <div class="mb-3">
                                            <span class="fst-italic fw-bold text-secondary">
                                                * Masukkan Kode OTP yang dikirim melalui Email
                                            </span>
                                        </div>
                                        <!-- Email Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-white border-1" id="basic-addon2">
                                                <i class="bi bi-envelope text-secondary"></i>
                                            </span>
                                            <input type="email" id="email" name="email"
                                                class="form-control border-1 py-2 px-0 @error('email') is-invalid @enderror"
                                                placeholder="Masukkan Email" value="{{ Auth::user()->email }}"
                                                aria-label="Masukkan Email" aria-describedby="basic-addon2" required
                                                readonly>
                                            @error('email')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- Kode OTP -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-white border-1" id="basic-addon2">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                            <input type="text" id="kode_otp" name="kode_otp"
                                                class="form-control border-1 py-2 px-0 @error('kode_otp') is-invalid @enderror"
                                                placeholder="Masukkan Kode OTP" value="{{ old('kode_otp') }}"
                                                aria-label="Masukkan Kode OTP" aria-describedby="basic-addon2" required>
                                            @error('kode_otp')
                                                <span class="invalid-feedback text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="d-flex flex-row">
                                            <!-- Link untuk Minta OTP -->
                                            <a id="requestOtpLink" href="{{ route('requestOtp') }}"
                                                class="btn btn-grey me-2">
                                                <i class="bi bi-send"></i> Minta OTP
                                            </a>

                                            <!-- Tombol countdown 2 menit -->
                                            <button id="otpCountdownButton" class="btn btn-grey me-2" type="button"
                                                disabled style="display: none;">
                                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>

                                                <span class="" role="status"> {{-- countdown 2 menit --}} </span>
                                            </button>

                                            <button type="submit" class="btn btn-secondary">
                                                <i class="bi bi-check-circle"></i> Verifikasi OTP
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <br>
                            <div class="d-flex justify-content-end">
                                {{-- Opsi 2 --}}
                                @if (Auth::user()->status === 'Aktif' && Auth::user()->email_verified_at !== null)
                                    @if (Auth::user()->level === 'Super Admin')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5" href="{{ route('super-admin.index') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </a>
                                    @elseif (Auth::user()->level === 'Admin Billper')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5" href="{{ route('adminbillper.index') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </a>
                                    @elseif (Auth::user()->level === 'Admin Pranpc')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5" href="{{ route('adminpranpc.index') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </a>
                                    @elseif (Auth::user()->level === 'Sales')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5"
                                            href="{{ route('assignmentbillper.index') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </a>
                                    @endif
                                @else
                                    <a class="btn btn-keluar fw-bold mt-5" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-in-left"></i> Keluar
                                    </a>
                                @endif
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const requestOtpLink = document.getElementById('requestOtpLink');
            const otpCountdownButton = document.getElementById('otpCountdownButton');

            function startCountdown(duration) {
                let timer = duration,
                    minutes, seconds;
                const interval = setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    otpCountdownButton.querySelector('span[role="status"]').textContent =
                        `Tunggu ${minutes}:${seconds}`;

                    if (--timer < 0) {
                        clearInterval(interval);
                        otpCountdownButton.style.display = 'none';
                        requestOtpLink.style.display = 'block';
                        localStorage.removeItem(
                            'otpExpiryTime'); // Hapus waktu kadaluarsa dari localStorage
                    } else {
                        localStorage.setItem('otpExpiryTime', Date.now() + timer *
                            1000); // Simpan waktu kadaluarsa di localStorage
                    }
                }, 1000);
            }

            function checkCountdown() {
                const expiryTime = localStorage.getItem('otpExpiryTime');
                if (expiryTime) {
                    const remainingTime = (expiryTime - Date.now()) / 1000;
                    if (remainingTime > 0) {
                        requestOtpLink.style.display = 'none';
                        otpCountdownButton.style.display = 'block';
                        startCountdown(remainingTime);
                    } else {
                        localStorage.removeItem('otpExpiryTime'); // Hapus waktu kadaluarsa jika sudah lewat
                    }
                }
            }

            requestOtpLink.addEventListener('click', function(e) {
                e.preventDefault();

                requestOtpLink.style.display = 'none';
                otpCountdownButton.style.display = 'block';
                startCountdown(120);

                fetch(requestOtpLink.href, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: '{{ Auth::user()->email }}'
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.status === 'Success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'OTP Dikirim',
                                text: 'Kode OTP berhasil dikirim ke email Anda.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#831a16'
                            });
                        } else {
                            throw new Error('Error response');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat meminta OTP. Silakan coba lagi.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#831a16'
                        });
                        otpCountdownButton.style.display = 'none';
                        requestOtpLink.style.display = 'block';
                    });
            });

            checkCountdown(); // Periksa countdown saat halaman dimuat
        });
    </script>
@endpush
