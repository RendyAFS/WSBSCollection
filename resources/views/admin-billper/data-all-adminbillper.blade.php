@extends('layouts.app-admin')

@include('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Data All
                <span id="info-filter" class="fs-6 fw-normal">

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
                            <form id="filterForm" action="{{ route('adminbillper.index') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="jenis_data">Jenis data</label>
                                        <select id="jenis_data_filter" name="jenis_data" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected value="Semua">Semua</option>
                                            <option value="Billper">Billper</option>
                                            <option value="Existing">Existing</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="nper_filter">Pilih NPER</label>
                                        <input type="month" id="nper_filter" name="nper_filter" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="status_pembayaran">Status Pembayaran</label>
                                        <select id="status_pembayaran_filter" name="status_pembayaran" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected value="Semua">Semua</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('all.index') }}" class="btn btn-grey">
                                        <i class="bi bi-x-lg"></i> Reset
                                    </a>
                                    <button type="button" id="btn-filter" class="btn btn-secondary btn-filter"
                                        data-bs-dismiss="modal">
                                        <i class="bi bi-funnel-fill"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- BTN Donwload --}}
                <div class="btn-group">
                    <a href="{{ route('download.exceladminbillper') }}" class="btn btn-green">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i> Download Semua
                    </a>
                    <button type="button" class="btn btn-green dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
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
                                <form id="downloadForm" action="{{ route('download.filtered.exceladminbillper') }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="nper">Pilih Bulan-Tahun</label>
                                            <input type="month" id="nper_download" name="nper" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="status_pembayaran">Status Pembayaran</label>
                                            <select id="status_pembayaran" name="status_pembayaran" class="form-select"
                                                aria-label="Default select example" required>
                                                <option selected value="Semua">Semua</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Unpaid">Unpaid</option>
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
        </div>

        <table class="table table-hover table-bordered datatable shadow" id="tabelallsadminbillper" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nper</th>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">No. Tlf</th>
                    <th id="th" class="align-middle">Email</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">NPER</th>
                    <th id="th" class="align-middle text-center">Umur Customer</th>
                    <th id="th" class="align-middle text-center">Produk</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">NPER</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        // Table initialization
        $(document).ready(function() {
            var dataTable = new DataTable('#tabelallsadminbillper', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelallsadminbillper') }}",
                    type: 'GET',
                    data: function(d) {
                        d.jenis_data = $('#jenis_data_filter').val();
                        d.nper = $('#nper_filter').val();
                        d.status_pembayaran = $('#status_pembayaran_filter').val();
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
                columns: [{
                        data: 'nper',
                        name: 'nper',
                        className: 'align-middle',
                        visible: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'no_inet',
                        name: 'no_inet',
                        className: 'align-middle'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'no_tlf',
                        name: 'no_tlf',
                        className: 'align-middle'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'align-middle'
                    },
                    {
                        data: 'sto',
                        name: 'sto',
                        className: 'align-middle'
                    },
                    {
                        data: 'nper',
                        name: 'nper',
                        className: 'align-middle'
                    },
                    {
                        data: 'umur_customer',
                        name: 'umur_customer',
                        className: 'align-middle'
                    },
                    {
                        data: 'produk',
                        name: 'produk',
                        className: 'align-middle',
                        visible: false
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran',
                        className: 'align-middle',
                        render: function(data, type, row) {
                            if (data === 'Unpaid') {
                                return '<span class="badge text-bg-warning">Unpaid</span>';
                            } else if (data === 'Paid') {
                                return '<span class="badge text-bg-success">Paid</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'nper',
                        name: 'nper',
                        visible: false,
                        className: 'align-middle'
                    },

                ],
                order: [
                    [9, 'asc']
                ],
                lengthMenu: [
                    [100, 500, 1000, -1],
                    [100, 500, 1000, "Semua"]
                ],
                language: {
                    search: "Cari",
                    lengthMenu: "Tampilkan _MENU_ data",
                }
            });

            $('#btn-filter').on('click', function() {
                var jenisData = $('#jenis_data_filter').val();
                var nper = $('#nper_filter').val();
                var statusPembayaran = $('#status_pembayaran_filter').val();

                var infoText = jenisData + " - " + nper + " - " + statusPembayaran;
                $('#info-filter').text(infoText);

                dataTable.ajax.reload();
            });
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Tambahkan titik jika ada ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp.' + rupiah : '');
        }

        // Validate filter download
        document.addEventListener('DOMContentLoaded', function() {
            const btnSave = document.getElementById('btn-filter-download');
            const bulanTahunInput = document.getElementById('nper_download');

            btnSave.addEventListener('click', function(event) {
                if (!bulanTahunInput.value) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap isi Bulan/Tahun terlebih dahulu!',
                    });
                }
            });
        });
    </script>
@endpush
