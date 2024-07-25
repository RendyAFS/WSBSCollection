@extends('layouts.app-admin')
@extends('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Report Billper - Existing
            </span>
        </div>

        {{-- Filter Form --}}
        <form id="filterForm" action="{{ route('report-all-adminbillper.index') }}" method="GET">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="month" class="form-label fw-bold">Bulan</label>
                    @php
                        // Array of month names in Indonesian
                        $months = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember',
                        ];
                    @endphp

                    <select id="month" name="month" class="form-control">
                        @foreach ($months as $value => $name)
                            <option value="{{ $value }}" {{ $filterMonth == $value ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label fw-bold mt-3 mt-md-0">Tahun</label>
                    <select id="year" name="year" class="form-control">
                        @for ($y = now()->year; $y >= 2000; $y--)
                            <option value="{{ $y }}" {{ $filterYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end justify-content-md-start">
                    <button type="submit" class="btn btn-secondary w-50 mt-3 mt-md-0">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No. </th>
                    <th scope="col">Jenis Voc Kendala</th>
                    <th scope="col">Total Reports</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voc_kendalas as $voc_kendala)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $voc_kendala->voc_kendala }}</td>
                        <td>{{ $voc_kendala->sales_reports_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Loading Screen --}}
    <div class="contain-loading d-none" id="loadingScreen">
        <div class="content-loading">
            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
                <img src="{{ Vite::asset('resources/images/logo-telkom.png') }}" alt="" id="img-loading">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterForm').addEventListener('submit', function(event) {
                document.getElementById('loadingScreen').classList.remove('d-none');

                setTimeout(function() {
                    document.getElementById('loadingScreen').classList.add('d-none');
                }, 1500);
            });
        });
    </script>
@endsection
