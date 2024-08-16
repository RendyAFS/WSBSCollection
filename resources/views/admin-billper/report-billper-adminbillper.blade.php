@extends('layouts.app-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4 mb-3">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">
                Report Sales Billper
            </span>
        </div>

        <div class="mb-4 ">
            <span class="fw-bold fs-2">
                Filter Data
            </span>
        </div>
        {{-- Filter Form --}}
        <form id="filterForm" action="{{ route('report-billper-adminbillper.index') }}" method="GET">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="month" class="form-label fw-bold">Bulan</label>
                    @php
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
                <div class="col-md-4">
                    <label for="filter_sales" class="form-label fw-bold mt-3 mt-md-0">Nama Sales</label>
                    <select id="filter_sales" name="filter_sales" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($sales as $sale)
                            <option value="{{ $sale->name }}" {{ $filterSales == $sale->name ? 'selected' : '' }}>
                                {{ $sale->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3 d-flex justify-content-end align-items-end">
                    <button type="submit" class="btn btn-secondary me-2 mt-3 mt-md-0">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>

                </div>
            </div>
        </form>
    </div>
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Report Progres Sales
            </span>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" class="align-middle">No.</th>
                    <th scope="col" class="align-middle">Nama Sales</th>
                    <th scope="col" class="text-center align-middle">WO</th>
                    <th scope="col" class="text-center align-middle">Total Visit</th>
                    <th scope="col" class="text-center align-middle">WO Sudah Visit</th>
                    <th scope="col" class="text-center align-middle">WO Belum Visit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                        <td class="align-middle">{{ $sale->name }}</td>
                        <td class="text-center align-middle">{{ $sale->total_assignment }}</td>
                        <td class="text-center align-middle">{{ $sale->total_visit }}</td>
                        <td class="text-center align-middle">{{ $sale->wo_sudah_visit }}</td>
                        <td class="text-center align-middle">{{ $sale->wo_belum_visit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div class="mb-4">
            <span class="fw-bold fs-2">
                Report Billper
            </span>
        </div>

        {{-- Table --}}
        <table class=" table table-hover">
            <thead>
                <tr>
                    <th scope="col" class="align-middle">No. </th>
                    <th scope="col" class="align-middle">Jenis Voc Kendala</th>
                    <th scope="col" class="text-center align-middle">Total Reports</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voc_kendalas as $voc_kendala)
                    <tr>
                        <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                        <td class="align-middle">{{ $voc_kendala->voc_kendala }}</td>
                        <td class="text-center align-middle">{{ $voc_kendala->sales_reports_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-5 mb-2 d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-2">
                Detail Billper
            </span>
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
                    <th id="th" class="align-middle text-center">Visit</th>
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
            var dataTable = new DataTable('#datareportbillper', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('getDatareportbillper') }}",
                    type: 'GET',
                    data: function(d) {
                        d.month = document.getElementById('month').value;
                        d.year = document.getElementById('year').value;
                        d.filter_sales = document.getElementById('filter_sales').value;
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
                    [2, 'asc']
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
                        data: 'billpers.nama',
                        name: 'billpers.nama',
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
                        data: 'jmlh_visit',
                        name: 'jmlh_visit',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'evidence',
                        name: 'evidence',
                        className: 'align-middle text-center'
                    }
                ]
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
