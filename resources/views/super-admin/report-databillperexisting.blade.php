@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Report Data Billper Existing
                <span id="info-filter">
                    @if (isset($nper) && !$show_all)
                        - {{ strftime('%B %Y', strtotime($nper)) }}
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
                            <form id="filterForm" action="{{ route('reportdatabillperexisting.index') }}" method="GET">
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="filter_type">Jenis Filter</label>
                                        <select id="filter_type" name="filter_type" class="form-select" required>
                                            <option value="sto" {{ $filter_type === 'sto' ? 'selected' : '' }}>STO
                                            </option>
                                            <option value="umur_customer"
                                                {{ $filter_type === 'umur_customer' ? 'selected' : '' }}>Umur Customer
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nper">Pilih Bulan-Tahun</label>
                                        <input type="month" id="nper" name="nper" value=""
                                            class="form-control">
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="flexCheckDefault"
                                                name="show_all" {{ $show_all ? 'checked' : '' }} checked>
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

                <!-- Button trigger modal Riwayat-->
                <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#modalRiwayat">
                    <i class="bi bi-clock-history"></i> Riwayat
                </button>

                <!-- Modal Riwayat-->
                <div class="modal fade" id="modalRiwayat" tabindex="-1" aria-labelledby="modalRiwayatLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalFilterdataLabel">Riwayat</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    @foreach ($riwayats as $riwayat)
                                        <li>
                                            Perubahan status pembayaran Nama File:
                                            <strong>{{ $riwayat->deskripsi_riwayat }}</strong> Bulan-Tahun NPER:
                                            <strong>{{ $riwayat->tanggal_riwayat }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('grafikdatabillperexisting.index') }}" class="btn btn-green ">
                    <i class="bi bi-clipboard-data"></i> Grafik
                </a>
            </div>
        </div>
    </div>
    <table id="tabel_report" class="table table-bordered shadow" style="width:100%;">
        <thead class="table-warning">
            <tr>
                <th id="th" class="align-middle">{{ $filter_type === 'umur_customer' ? 'Umur Customer' : 'STO' }}
                </th>
                <th id="th" class="align-middle text-center">Total SSL</th>
                <th id="th" class="align-middle text-center">Saldo Awal</th>
                <th id="th" class="align-middle text-center">Paid</th>
                <th id="th" class="align-middle text-center">Unpaid</th>
                <th id="th" class="align-middle text-center">Pending</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td id="td" class="align-middle">
                        {{ $filter_type === 'umur_customer' ? $report->umur_customer : $report->sto }}</td>
                    <td id="td" class="align-middle text-center">{{ $report->total_ssl }}</td>
                    <td id="td" class="align-middle text-center">
                        Rp{{ number_format($report->total_saldo, 0, ',', '.') }}</td>
                    <td id="td" class="align-middle text-center">
                        Rp{{ number_format($report->total_paid, 0, ',', '.') }}</td>
                    <td id="td" class="align-middle text-center">
                        Rp{{ number_format($report->total_unpaid, 0, ',', '.') }}</td>
                    <td id="td" class="align-middle text-center">
                        Rp{{ number_format($report->total_pending, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="table-secondary">
            <tr>
                <td class="align-middle fw-bold">Total</td>
                <td class="align-middle fw-bold text-center">{{ $total_ssl }}</td>
                <td class="align-middle fw-bold text-center">Rp{{ number_format($total_saldo, 0, ',', '.') }}</td>
                <td class="align-middle fw-bold text-center">Rp{{ number_format($total_paid, 0, ',', '.') }}</td>
                <td class="align-middle fw-bold text-center">Rp{{ number_format($total_unpaid, 0, ',', '.') }}
                </td>
                <td class="align-middle fw-bold text-center">Rp{{ number_format($total_pending, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            var flexCheckDefault = document.getElementById('flexCheckDefault');
            var nperInput = document.getElementById('nper');

            // Mengatur status disabled dan nilai kosong ketika flexCheckDefault diubah
            flexCheckDefault.addEventListener('change', function() {
                if (flexCheckDefault.checked) {
                    nperInput.disabled = true;
                    nperInput.value = ''; // Mengosongkan nilai
                } else {
                    nperInput.disabled = false;
                    // Anda bisa mengatur nilai default kembali jika diperlukan
                }
            });

            // Inisialisasi kondisi awal
            if (flexCheckDefault.checked) {
                nperInput.disabled = true;
                nperInput.value = ''; // Mengosongkan nilai
            }

            // Menangani submit form
            document.getElementById('filterForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form standar

                var nper = nperInput.value;
                var filterType = document.getElementById('filter_type').value;

                var infoFilterDiv = document.getElementById('info-filter');
                infoFilterDiv.textContent = `${filterType} - ${nper}`;

                // Sekarang kirim form
                event.target.submit();
            });
        });

        // Tabel Report data
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
    </script>
@endpush
