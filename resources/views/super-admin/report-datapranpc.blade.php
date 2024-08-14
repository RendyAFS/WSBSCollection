@extends('layouts.app-super-admin')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                <span class="d-block d-md-none">
                    Report Data Pranpc
                </span>
                <span id="info-filter">
                    @if (isset($year) && isset($bulan) && !$show_all)
                        {{ $year }} ({{ $bulan }})
                    @endif
                </span>
            </span>
            <div class="d-flex">
                <!-- Button trigger modal Filter Data-->
                <button type="button" class="btn btn-white me-2" data-bs-toggle="modal" data-bs-target="#modalFilterdata">
                    <i class="bi bi-funnel-fill"></i> Filter Data
                </button>

                <!-- Modal Filter Data-->
                <div class="modal fade" id="modalFilterdata" tabindex="-1" aria-labelledby="modalFilterdataLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalFilterdataLabel">Filter Data</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="filterForm" action="{{ route('reportdatapranpc.index') }}" method="GET">
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="year">Pilih Tahun</label>
                                        <input type="number" id="year_filter" name="year" class="form-control"
                                            min="1900" max="2100" step="1" required
                                            value="{{ $year }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="bulan">Pilih Bulan</label>
                                        <select id="bulan_filter" name="bulan" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected value="Semua" disabled>Pilih Rentang Bulan</option>
                                            <option value="01-02" {{ $bulan === '01-02' ? 'selected' : '' }}>Januari -
                                                Februari</option>
                                            <option value="02-03" {{ $bulan === '02-03' ? 'selected' : '' }}>Februari -
                                                Maret</option>
                                            <option value="03-04" {{ $bulan === '03-04' ? 'selected' : '' }}>Maret - April
                                            </option>
                                            <option value="04-05" {{ $bulan === '04-05' ? 'selected' : '' }}>April - Mei
                                            </option>
                                            <option value="05-06" {{ $bulan === '05-06' ? 'selected' : '' }}>Mei - Juni
                                            </option>
                                            <option value="06-07" {{ $bulan === '06-07' ? 'selected' : '' }}>Juni - Juli
                                            </option>
                                            <option value="07-08" {{ $bulan === '07-08' ? 'selected' : '' }}>Juli -
                                                Agustus</option>
                                            <option value="08-09" {{ $bulan === '08-09' ? 'selected' : '' }}>Agustus -
                                                September</option>
                                            <option value="09-10" {{ $bulan === '09-10' ? 'selected' : '' }}>September -
                                                Oktober</option>
                                            <option value="10-11" {{ $bulan === '10-11' ? 'selected' : '' }}>Oktober -
                                                November</option>
                                            <option value="11-12" {{ $bulan === '11-12' ? 'selected' : '' }}>November -
                                                Desember</option>
                                            <option value="12-01" {{ $bulan === '12-01' ? 'selected' : '' }}>Desember -
                                                Januari</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault"
                                                name="show_all" {{ $show_all ? 'checked' : '' }}>
                                            <label class="form-check-label text-secondary" for="flexCheckDefault">
                                                Tampilkan Semua
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grey" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-secondary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <span class="fw-bold fst-italic">* Last Update: {{ $lastUpdate }}</span>

        <table id="tabel_report" class="table table-bordered shadow" style="width:100%;">
            <thead class="table-warning">
                <tr>
                    <th id="th" class="align-middle">STO</th>
                    <th id="th" class="align-middle text-center">Total SSL</th>
                    <th id="th" class="align-middle text-center">Bill Bulan</th>
                    <th id="th" class="align-middle text-center">Bill Bulan1</th>
                    <th id="th" class="align-middle text-center">Paid Bill Bulan</th>
                    <th id="th" class="align-middle text-center">Paid Bill Bulan1</th>
                    <th id="th" class="align-middle text-center">Unpaid Bill Bulan</th>
                    <th id="th" class="align-middle text-center">Unpaid Bill Bulan1</th>
                    <th id="th" class="align-middle text-center">Pending Bill Bulan</th>
                    <th id="th" class="align-middle text-center">Pending Bill Bulan1</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->sto }}</td>
                        <td class="text-center align-middle">{{ $report->total_ssl }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_bill_bln) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_bill_bln1) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_paid_bill_bln) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_paid_bill_bln1) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_unpaid_bill_bln) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_unpaid_bill_bln1) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_pending_bill_bln) }}</td>
                        <td class="text-center align-middle">Rp.{{ number_format($report->total_pending_bill_bln1) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-secondary">
                <tr>
                    <td class="text-center align-middle fw-bold">Total</td>
                    <td class="text-center align-middle fw-bold">{{ $total_ssl }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_bill_bln) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_bill_bln1) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_paid_bill_bln) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_paid_bill_bln1) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_unpaid_bill_bln) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_unpaid_bill_bln1) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_pending_bill_bln) }}</td>
                    <td class="text-center align-middle fw-bold">Rp.{{ number_format($total_pending_bill_bln1) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection


@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            var flexCheckDefault = document.getElementById('flexCheckDefault');
            var yearInput = document.getElementById('year_filter');
            var bulanInput = document.getElementById('bulan_filter');

            flexCheckDefault.addEventListener('change', function() {
                if (flexCheckDefault.checked) {
                    yearInput.disabled = true;
                    bulanInput.disabled = true;
                } else {
                    yearInput.disabled = false;
                    bulanInput.disabled = false;
                }
            });

            if (flexCheckDefault.checked) {
                yearInput.disabled = true;
                bulanInput.disabled = true;
            }

            document.getElementById('filterForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var year = yearInput.value;
                var bulan = bulanInput.value;

                var infoFilterDiv = document.getElementById('info-filter');
                infoFilterDiv.textContent = `${year} (${bulan})`;

                event.target.submit();
            });
        });

        $(document).ready(function() {
            var dataTable = new DataTable('#tabel_report', {
                pagingType: 'simple',
                responsive: true,
                lengthMenu: [
                    [10, -1],
                    [10, 'Semua']
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
            });
        });
        // Tahun Now Filter
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan elemen input tahun
            var yearInput = document.getElementById('year_filter');

            // Dapatkan tahun sekarang
            var currentYear = new Date().getFullYear();

            // Set nilai default input tahun menjadi tahun sekarang
            yearInput.value = currentYear;
        });
    </script>
@endpush
