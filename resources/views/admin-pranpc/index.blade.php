@extends('layouts.app-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">
                Dashboard
            </span>
        </div>

        <div class="dashbooard-admin-wraper">
            {{-- Baris 1 --}}
            <div class="row mb-0 mb-xl-3">
                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 py-3">
                        @include('components.admin-analisis-data-pranpc')
                    </div>
                </div>
                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 py-3">
                        @include('components.admin-analisis-data-existing')

                    </div>
                </div>
            </div>
            {{-- Baris 2 --}}
            <div class="row">
                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 py-3">
                        @include('components.admin-tabel-pending-pranpcexsititng')
                    </div>
                </div>
                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                    <div class="card border border-0 shadow shadow-sm rounded-4 px-3 py-3">
                        <div class="d-flex justify-content-between w-100 mb-3">
                            <div class="text-left">
                                <span class="fs-3">
                                    Progress Sales
                                </span>
                                <div class="mt-1">
                                    <span class="text-secondary">
                                        Sales dengan aktifitas tertinggi dan terendah.
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="fw-bold fs-2">
                                    {{ number_format($totalVisitpranpcexistingSales, 0, ',', '.') }}
                                    <span class="fs-5">
                                        Visit
                                    </span>
                                </span>
                                <div class="mt-1">
                                    <span class="text-secondary">Total visit semua sales pada bulan ini.</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column content-2">
                            <div class="head-progres-sales text-center my-5">
                                <span class="fw-bold fs-4"> Aktivitas Tertinggi </span>
                            </div>
                            <div class="d-flex flex-column flex-xl-row">
                                <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                                    @include('components.adminpranpc-aktivitas-tertinggi-data-pranpc')
                                </div>
                                <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                                    @include('components.adminpranpc-aktivitas-tertinggi-data-existing')
                                </div>
                            </div>
                            <div class="head-progres-sales text-center my-5">
                                <span class="fw-bold fs-4"> Aktivitas Terendah </span>
                            </div>
                            <div class="d-flex flex-column flex-xl-row">
                                <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                                    @include('components.adminpranpc-aktivitas-terendah-data-pranpc')
                                </div>
                                <div class="card border border-0 shadow shadow-sm rounded-4 px-3 w-100 me-3 mb-3 mb-xl-0">
                                    @include('components.adminpranpc-aktivitas-terendah-data-existing')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
