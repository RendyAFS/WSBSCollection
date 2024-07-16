@extends('layouts.app')

@section('content')
    <div class="home-bg">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-md-8 mt-md-5 mt-5 pt-md-0 pt-5">
                    <h1 class="home-h1 mb-4">Akun Sudah Terdaftar</h1>
                    <div class="card">
                        <div class="card-header fs-5 fw-bold" id="calc-stunting">Informasi</div>
                        <div class="d-flex justify-content-center mt-3 mb-4">
                            <img src="{{ Vite::asset('resources/images/logo-telkom.png') }}" alt=""
                                id="home-logo-telkom">
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            Akun <b>{{ Auth::user()->name }}</b> sudah Terdaftar dengan Status akun
                            @if (Auth::user()->status === 'Aktif')
                                <b class="text-success">{{ Auth::user()->status }}</b>!
                            @else
                                <b class="text-danger">{{ Auth::user()->status }}</b>!
                            @endif
                            <br>
                            <div class="d-flex justify-content-end">

                                {{-- Opsi 1 --}}
                                {{-- <a class="btn btn-keluar fw-bold mt-5" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-in-left"></i> Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form> --}}

                                {{-- Opsi 2 --}}
                                @if (Auth::user()->status === 'Aktif')
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
                                    @elseif (Auth::user()->level === 'Admin PraNPC')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5" href="{{ route('adminpranpc.index') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                                        </a>
                                    @elseif (Auth::user()->level === 'User')
                                        <a class="btn btn-keluar fw-bold mt-5 me-2" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-in-left"></i> Keluar
                                        </a>
                                        <a class="btn btn-masuk fw-bold mt-5" href="{{ route('user.index') }}">
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
