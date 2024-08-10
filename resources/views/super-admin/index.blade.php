@extends('layouts.app-super-admin')

@section('content')
    <div class="mb-4 d-block d-md-none">
        <span class="fw-bold fs-2">
            Dashboard
        </span>
    </div>

    <div class="dashbooard-wraper">
        <div class="dashboard-side-kiri">
            {{-- Side Kiri --}}
            {{-- Baris 1 --}}
            <div class="card-content">
                <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                    @include('components.analisis-data-billper')
                </div>
            </div>
            <div class="card-content">
                <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                    @include('components.analisis-data-existing')
                </div>
            </div>
            <div class="card-content">
                <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                    @include('components.analisis-data-pranpc')
                </div>
            </div>

            {{-- Baris 2 --}}
            <div
                class="w-100 d-flex flex-column bg-white border border-0 shadow shadow-sm rounded-4 px-4 py-4 wrapper-content-2">
                <div class="d-flex justify-content-between w-100 mb-3">
                    <div class="text-left">
                        <span class="fs-3">
                            Produk Populer
                        </span>
                        <div class="mt-1">
                            <span class="text-secondary">
                                Produk yang paling diminati
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="fw-bold fs-2">
                            {{ number_format($produkCountBillperExisting, 0, ',', '.') }}
                            <span class="fs-5">
                                Produk
                            </span>
                        </span>
                        <div class="mt-1">
                            <span class="text-secondary">Total visit semua sales pada bulan ini</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    {{-- Diver --}}
                </div>
                <div class="d-flex flex-column flex-xl-row content-2">
                    <div class="w-100 w-xl-50 border border-0 shadow shadow-sm rounded-4 px-3 me-3 mb-3 mb-xl-0">
                        @include('components.chart-produk-billper')
                    </div>
                    <div class="w-100 w-xl-50 border border-0 shadow shadow-sm rounded-4 px-3">
                        @include('components.chart-produk-existing')
                    </div>
                </div>
            </div>

            {{-- Baris 3 --}}
            <div
                class="w-100 d-flex flex-column bg-white border border-0 shadow shadow-sm rounded-4 px-4 py-4 wrapper-content-2">
                <div class="d-flex justify-content-between w-100 mb-3">
                    <div class="text-left">
                        <span class="fs-3">
                            Progress Sales
                        </span>
                        <div class="mt-1">
                            <span class="text-secondary">
                                Sales dengan aktifitas terbanyak dan sedikit
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="fw-bold fs-2">
                            {{ number_format($totalVisitSales, 0, ',', '.') }}
                            <span class="fs-5">
                                Visit
                            </span>
                        </span>
                        <div class="mt-1">
                            <span class="text-secondary">Total produk yang dijual pada bulan ini</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column content-2">
                    <div class="head-progres-sales text-center my-5">
                        <span class="fw-bold fs-4"> Aktivitas Teringgi </span>
                    </div>
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-tertinggi-data-billper')
                        </div>
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-tertinggi-data-existing')
                        </div>
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-tertinggi-data-pranpc')
                        </div>
                    </div>
                    <div class="head-progres-sales text-center my-5">
                        <span class="fw-bold fs-4"> Aktivitas Terendah </span>
                    </div>
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-terendah-data-billper')
                        </div>
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-terendah-data-existing')
                        </div>
                        <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                            @include('components.aktivitas-terendah-data-pranpc')
                        </div>
                    </div>
                </div>
            </div>

        </div>



        {{-- Side Kanan --}}
        <div class="dashboard-side-kanan">
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3 mb-3">
                <div class="card-body">
                    <div class="head-card mb-3 text-center d-flex flex-column mb-3">
                        <span class="fs-3">Kendala Report</span>
                        <span>
                            Kendala teratas berdasarkan STO
                            pada bulan ini.
                        </span>
                    </div>
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 mb-3">
                        @include('components.chart-kendala-billper')
                    </div>
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 mb-3">
                        @include('components.chart-kendala-existing')
                    </div>
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 mb-3">
                        @include('components.chart-kendala-pranpc')
                    </div>
                </div>
            </div>
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                <div class="card-body">
                    <div class="head-card mb-3 text-center d-flex flex-column mb-3">
                        <span class="fs-3">Data Akun</span>
                    </div>
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 mb-3">
                        @include('components.data-akun-dashboard')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
