@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Report Data
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
                            <form id="filterForm" action="{{ route('reportdata.index') }}" method="GET">
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="filter_type">Jenis Filter</label>
                                        <select id="filter_type" name="filter_type" class="form-select" required>
                                            <option value="sto">STO</option>
                                            <option value="umur_customer">Umur Customer</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nper">Pilih Bulan-Tahun</label>
                                        <input type="month" id="nper" name="nper" class="form-control" required>
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
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalRiwayat">
                    <i class="bi bi-clock-history"></i> Riwayat
                </button>

                <!-- Modal Riwayat-->
                <div class="modal fade" id="modalRiwayat" tabindex="-1" aria-labelledby="modalRiwayatLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
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
                                            <strong>{{ $riwayat->tanggal_riwayat}}</strong>
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
            </tr>
        </thead>
        <tbody id="tabel-report-body">
            @foreach ($reports as $data)
                <tr>
                    <td class="align-middle">{{ $filter_type === 'umur_customer' ? $data->umur_customer : $data->sto }}
                    </td>
                    <td class="align-middle text-center">{{ $data->total_ssl }}</td>
                    <td class="align-middle text-center">{{ 'Rp' . number_format($data->total_saldo, 0, ',', '.') }}
                    </td>
                    <td class="align-middle text-center">{{ 'Rp' . number_format($data->total_paid, 0, ',', '.') }}</td>
                    <td class="align-middle text-center">{{ 'Rp' . number_format($data->total_unpaid, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-secondary">
                <td class="align-middle"><strong>Total</strong></td>
                <td class="align-middle text-center"><strong>{{ $total_ssl }}</strong></td>
                <td class="align-middle text-center">
                    <strong>{{ 'Rp' . number_format($total_saldo, 0, ',', '.') }}</strong>
                </td>
                <td class="align-middle text-center">
                    <strong>{{ 'Rp' . number_format($total_paid, 0, ',', '.') }}</strong>
                </td>
                <td class="align-middle text-center">
                    <strong>{{ 'Rp' . number_format($total_unpaid, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
    </div>
@endsection
@push('scripts')
    <script type="module">
        // Tabel Antrian
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
                },
            });
        });


        // Default Date Filter
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const defaultValue = `${year}-${month}`;
            document.getElementById('nper').value = defaultValue;
        });
    </script>
@endpush
