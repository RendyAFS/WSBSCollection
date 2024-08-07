@extends('layouts.app-super-admin')

@section('content')
    <div class="mb-4 d-block d-md-none">
        <span class="fw-bold fs-2">
            Dashboard
        </span>
    </div>

    <div class="row">
        {{-- Side Kiri --}}
        <div class="col-xxl-3 col-xl-4 col-lg-6 mb-3 mb-xxl-0">
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                <div class="card-body">
                    <div class="head-card mb-3">
                        <span class="fs-5">Pelanggan Billper</span>
                    </div>
                    <div class="card-content mb-3 d-flex justify-content-end">
                        <span class="fw-bold fs-2">
                            {{ number_format($billperCount, 0, ',', '.') }}
                            <span class="fs-5">
                                Pelanggan
                            </span>
                        </span>
                    </div>
                    <div class="footer-card d-flex justify-content-end">
                        <span
                            class="percent-footer-card me-2 fw-bold {{ $percentBillper > 0 ? 'text-success' : ($percentBillper < 0 ? 'text-danger' : 'text-secondary') }}">
                            {{-- persen --}}
                            @if ($percentBillper > 0)
                                <i class="bi bi-arrow-up"></i>
                            @elseif ($percentBillper < 0)
                                <i class="bi bi-arrow-down"></i>
                            @endif
                            {{ number_format(abs($percentBillper), 2, ',', '.') }}%
                        </span>
                        <span class="font-grey text-footer-card">
                            dari bulan sebelumnya.
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-6 mb-3 mb-xxl-0">
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                <div class="card-body">
                    <div class="head-card mb-3">
                        <span class="fs-5">Pelanggan Existing</span>
                    </div>
                    <div class="card-content mb-3 d-flex justify-content-end">
                        <span class="fw-bold fs-2">
                            {{ number_format($existingCount, 0, ',', '.') }}
                            <span class="fs-5">
                                Pelanggan
                            </span>
                        </span>
                    </div>
                    <div class="footer-card d-flex justify-content-end">
                        <span
                            class="percent-footer-card me-2 fw-bold {{ $percentExisting > 0 ? 'text-success' : ($percentExisting < 0 ? 'text-danger' : 'text-secondary') }}">
                            {{-- persen --}}
                            @if ($percentExisting > 0)
                                <i class="bi bi-arrow-up"></i>
                            @elseif ($percentExisting < 0)
                                <i class="bi bi-arrow-down"></i>
                            @endif
                            {{ number_format(abs($percentExisting), 2, ',', '.') }}%
                        </span>
                        <span class="font-grey text-footer-card">
                            dari bulan sebelumnya.
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-4 col-lg-12 mb-3 mb-xxl-0">
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                <div class="card-body">
                    <div class="head-card mb-3">
                        <span class="fs-5">Data Master</span>
                    </div>
                    <div class="card-content mb-3 d-flex justify-content-end">
                        <span class="fw-bold fs-2">
                            {{ number_format($dataMasterCount, 0, ',', '.') }}
                            <span class="fs-5">
                                Pelanggan
                            </span>
                        </span>
                    </div>
                    <div class="footer-card d-flex justify-content-end">
                        <span
                            class="percent-footer-card me-2 fw-bold {{ $percentDataMaster > 0 ? 'text-success' : ($percentDataMaster < 0 ? 'text-danger' : 'text-secondary') }}">
                            {{-- persen --}}
                            @if ($percentDataMaster > 0)
                                <i class="bi bi-arrow-up"></i>
                            @elseif ($percentDataMaster < 0)
                                <i class="bi bi-arrow-down"></i>
                            @endif
                            @php
                                function formatNumber($number, $decimal = 2)
                                {
                                    $number = abs($number);
                                    if ($number >= 1000 && $number < 1000000) {
                                        return number_format($number / 1000, $decimal) . 'K';
                                    } elseif ($number >= 1000000 && $number < 1000000000) {
                                        return number_format($number / 1000000, $decimal) . 'M';
                                    } elseif ($number >= 1000000000) {
                                        return number_format($number / 1000000000, $decimal) . 'B';
                                    }
                                    return number_format($number, $decimal, ',', '.');
                                }
                            @endphp
                            {{ formatNumber($percentDataMaster, 2) }}%
                        </span>
                        <span class="font-grey text-footer-card">
                            dari bulan sebelumnya.
                        </span>
                    </div>

                </div>
            </div>
        </div>





        {{-- Side Kanan --}}
        <div class="col-xxl-3 col-xl-12 col-lg-12 mb-3 mb-xxl-0">
            <div class="card border border-0 shadow shadow-sm rounded-4 px-3">
                <div class="card-body">
                    This is some text within a card body.
                </div>
            </div>
        </div>
    </div>
@endsection
