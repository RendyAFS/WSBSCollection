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
        <div class="mt-5 mb-2 d-flex justify-content-end">
            <div class="btn-group">
                <a href="{{ route('download.excelreportbillper') }}" class="btn btn-green">
                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Download Semua
                </a>
                <button type="button" class="btn btn-green dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu p-3">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="fs-6" id="exampleModalLabel">Filter Download</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="downloadForm" action="{{ route('download.filtered.excelreportbillper') }}"
                                method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="tahun_bulan">Pilih Bulan-Tahun</label>
                                        <input type="month" id="tahun_bulan" name="tahun_bulan" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nama_sales">Nama Sales</label>
                                        <select id="nama_sales" name="nama_sales" class="form-select">
                                            <option value="">Semua</option>
                                            @foreach ($sales as $sale)
                                                <option value="{{ $sale->name }}">{{ $sale->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="voc_kendala">VOC & Kendala</label>
                                        <select id="voc_kendala" name="voc_kendala" class="form-select">
                                            <option value="">Semua</option>
                                            @foreach ($voc_kendalas as $voc_kendala)
                                                <option value="{{ $voc_kendala->voc_kendala }}">
                                                    {{ $voc_kendala->voc_kendala }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" id="btn-filter-download"
                                        class="btn btn-green btn-filter-download">
                                        <i class="bi bi-file-earmark-spreadsheet-fill"></i> Download
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
        {{-- New Table --}}
        <table class="table table-hover table-bordered datatable shadow" id="datareportbillper" style="width: 100%">
            <thead>
                <tr>
                    <th id="th" class="align-middle text-center">SND</th>
                    <th id="th" class="align-middle text-center">Nama Customer</th>
                    <th id="th" class="align-middle text-center">Waktu Visit</th>
                    <th id="th" class="align-middle text-center">Nama Sales</th>
                    <th id="th" class="align-middle text-center">VOC & Kendala</th>
                    <th id="th" class="align-middle text-center">Follow Up</th>
                    <th id="th" class="align-middle text-center">Evidence</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data will be populated by DataTables --}}
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterForm').addEventListener('submit', function(event) {
                document.getElementById('loadingScreen').classList.remove('d-none');

                setTimeout(function() {
                    document.getElementById('loadingScreen').classList.add('d-none');
                }, 1500);
            });

            // Initialize DataTable
            // Initialize DataTable
            var dataTable = new DataTable('#datareportbillper', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('getDatareportbillper') }}",
                    type: 'GET',
                    data: function(d) {
                        // Additional parameters can be added here if needed
                    },
                    beforeSend: function() {
                        $('#loadingScreen').removeClass('d-none');
                    },
                    complete: function() {
                        $('#loadingScreen').addClass('d-none');
                    },
                    error: function() {
                        $('#loadingScreen').addClass('d-none');
                    }
                },
                order: [
                    [2, 'desc']
                ],
                lengthMenu: [
                    [100, 500, 1000, -1],
                    [100, 500, 1000, "Semua"]
                ],
                language: {
                    search: "Cari",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Tidak ada data ditemukan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                },
                columns: [{
                        data: 'snd',
                        name: 'snd',
                        className: 'align-middle text-center'

                    },
                    {
                        data: 'alls.nama',
                        name: 'alls.nama',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'waktu_visit',
                        name: 'waktu_visit',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'vockendals.voc_kendala',
                        name: 'vockendals.voc_kendala',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'follow_up',
                        name: 'follow_up',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'evidence',
                        name: 'evidence',
                        className: 'align-middle text-center'
                    },
                ],

            });
        });
        // Input date now
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('tahun_bulan');
            var now = new Date();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var year = now.getFullYear();
            var defaultDate = year + '-' + month;
            dateInput.value = defaultDate;
        });
    </script>
@endpush
