@extends('layouts.app-guest')

@section('content')
    <div class="content">
        <div class="left-side">
            <div class="login-box">
                <div class="header-landingpage text-center mt-5 mb-5 pb-5 d-block d-md-none">
                    <img src="{{  asset('storage/file_assets/logo-telkom.png') }}" alt="Logo 1" class="logohead">
                </div>
                <div class="d-flex flex-column align-items-center mt-5 pt-5">
                    <span class="fs-2 fw-bold"> Witel SBS Data Collection </span>
                    <hr class="hr-landingpage border border-white border-1 mt-0 mb-4 opacity-100 w-100">
                    <a href="/login" class="btn btn-menu w-100 mb-3">
                        <span class="fs-5">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </span>
                    </a>
                </div>
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
