@extends('layouts.app-super-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                <span class="d-block d-md-none">
                    Data Pranpc
                </span>
                <span id="info-filter" class="fs-6 fw-normal">

                </span>
            </span>

            <div class="d-flex flex-column flex-lg-row">
                <!-- Button trigger modal Filter Data-->
                <button type="button" class="btn btn-white me-2 mb-2 mb-xl-0" data-bs-toggle="modal"
                    data-bs-target="#modalFilterdata">
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
                            <form id="filterForm" action="{{ route('pranpc.index') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="year">Pilih Tahun</label>
                                        <input type="number" id="year_filter" name="year" class="form-control"
                                            min="1900" max="2100" step="1" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="bulan">Pilih Bulan</label>
                                        <select id="bulan_filter" name="bulan" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected value="Semua" disabled>Pilih Rentang Bulan</option>
                                            <option value="01-02">Januari - Februari</option>
                                            <option value="02-03">Februari - Maret</option>
                                            <option value="03-04">Maret - April</option>
                                            <option value="04-05">April - Mei</option>
                                            <option value="05-06">Mei - Juni</option>
                                            <option value="06-07">Juni - Juli</option>
                                            <option value="07-08">Juli - Agustus</option>
                                            <option value="08-09">Agustus - September</option>
                                            <option value="09-10">September - Oktober</option>
                                            <option value="10-11">Oktober - November</option>
                                            <option value="11-12">November - Desember</option>
                                            <option value="12-01">Desember - Januari</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="status_pembayaran">Status Pembayaran</label>
                                        <select id="status_pembayaran_filter" name="status_pembayaran" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected value="Semua">Semua</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <a href="{{ route('pranpc.index') }}" class="btn btn-grey">
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

                {{-- Button Riwayat --}}
                <a class="btn btn-white me-2 mb-2 mb-xl-0" href="{{ route('pranpcriwayat.index') }}" role="button"><i
                        class="bi bi-clock-fill"></i> Riwayat</a>

                {{-- BTN DOWNLOAD --}}
                <div class="btn-group">
                    <a href="{{ route('download.excelpranpc') }}" class="btn btn-green">
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
                                <form id="downloadForm" action="{{ route('download.filtered.excelpranpc') }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="year">Pilih Tahun</label>
                                            <input type="number" id="year_download" name="year" class="form-control"
                                                min="1900" max="2100" step="1" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="bulan">Pilih Bulan</label>
                                            <select id="bulan" name="bulan" class="form-select"
                                                aria-label="Default select example" required>
                                                <option selected disabled>Pilih Rentang Bulan</option>
                                                <option value="01-02">Januari - Februari</option>
                                                <option value="02-03">Februari - Maret</option>
                                                <option value="03-04">Maret - April</option>
                                                <option value="04-05">April - Mei</option>
                                                <option value="05-06">Mei - Juni</option>
                                                <option value="06-07">Juni - Juli</option>
                                                <option value="07-08">Juli - Agustus</option>
                                                <option value="08-09">Agustus - September</option>
                                                <option value="09-10">September - Oktober</option>
                                                <option value="10-11">Oktober - November</option>
                                                <option value="11-12">November - Desember</option>
                                                <option value="12-01">Desember - Januari</option>
                                            </select>
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

        <span class="fw-bold fst-italic">* Last Update: {{ $lastUpdate }}</span>

        <table class="table table-hover table-bordered datatable shadow" id="tabelpranpcs" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">mintgk</th>
                    <th id="th" class="align-middle text-center">maxtgk</th>
                    <th id="th" class="align-middle text-center">Bill Bln</th>
                    <th id="th" class="align-middle text-center">Bill Bln1</th>
                    <th id="th" class="align-middle text-center">No Hp</th>
                    <th id="th" class="align-middle">Email</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        // Table initialization
        $(document).ready(function() {
            var dataTable = new DataTable('#tabelpranpcs', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelpranpcs') }}",
                    type: 'GET',
                    data: function(d) {
                        d.year = $('#year_filter').val();
                        d.bulan = $('#bulan_filter').val();
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
                        data: 'nama',
                        name: 'nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'snd',
                        name: 'snd',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'sto',
                        name: 'sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'mintgk',
                        name: 'mintgk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'maxtgk',
                        name: 'maxtgk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'bill_bln',
                        name: 'bill_bln',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'bill_bln1',
                        name: 'bill_bln1',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'multi_kontak1',
                        name: 'multi_kontak1',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            if (data === 'Unpaid') {
                                return '<span class="badge text-bg-warning">Unpaid</span>';
                            } else if (data === 'Pending') {
                                return '<span class="badge text-bg-Secondary">Pending</span>';
                            } else if (data === 'Paid') {
                                return '<span class="badge text-bg-success">Paid</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'opsi-tabel-datapranpc',
                        name: 'opsi-tabel-datapranpc',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                lengthMenu: [
                    [100, 500],
                    [100, 500]
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

            $('#btn-filter').on('click', function() {
                var yearFilter = $('#year_filter').val();
                var bulanFilter = $('#bulan_filter').val();
                var statusPembayaran = $('#status_pembayaran_filter').val();

                var infoText = yearFilter + "-" + bulanFilter + " - " + statusPembayaran;
                $('#info-filter').text(infoText);

                dataTable.ajax.reload();
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

        // Tahun Now Download
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan elemen input tahun
            var yearInput = document.getElementById('year_download');

            // Dapatkan tahun sekarang
            var currentYear = new Date().getFullYear();

            // Set nilai default input tahun menjadi tahun sekarang
            yearInput.value = currentYear;
        });

        function formatRupiah(angka, prefix) {
            var numberString = angka.replace(/[^,\d]/g, '').toString(),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        // Modal delete confirmation
        $(".datatable").on("click", ".btn-delete", function(e) {
            e.preventDefault();

            var form = $(this).closest("form");

            Swal.fire({
                title: "Apakah Anda Yakin Menghapus Data " + "?",
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                confirmButtonColor: '#831a16',
                cancelButtonColor: '#727375'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Validate filter download
        document.addEventListener('DOMContentLoaded', function() {
            const btnSave = document.getElementById('btn-filter-download');
            const bulanTahunInput = document.getElementById('bulan');

            btnSave.addEventListener('click', function(event) {
                if (!bulanTahunInput.value || bulanTahunInput.value === "Pilih Rentang Bulan") {
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
