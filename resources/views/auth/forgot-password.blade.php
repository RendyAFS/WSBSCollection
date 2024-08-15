@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="left-side">
            <div class="login-box">
                <div class="d-flex flex-row mb-3">
                    <a href="/login" class="text-white">
                        <i class="bi bi-chevron-left me-3 fs-4"></i>
                    </a>
                    <span class="h2">Reset Password</span>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('resetPassword') }}">
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
                            <span class="invalid-feedback text-white" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" id="submitButton" class="btn-login mt-3">
                        <i class="bi bi-box-arrow-in-right"></i> Submit
                    </button>
                    <button id="loadingButton" class="btn-login mt-3 d-none" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span class="visually" role="status">Loading...</span>
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
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitButton = document.getElementById('submitButton');
            const loadingButton = document.getElementById('loadingButton');

            form.addEventListener('submit', function(event) {
                // Menampilkan tombol loading dan menyembunyikan tombol submit
                submitButton.classList.add('d-none');
                loadingButton.classList.remove('d-none');
            });
        });
    </script>
@endpush
