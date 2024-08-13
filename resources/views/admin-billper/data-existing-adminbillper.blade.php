@extends('layouts.app-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                <span class="d-block d-md-none">
                    Data Plotting Existing
                </span>
                <span id="info-filter" class="fs-6 fw-normal">
                    {{-- Info --}}
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
                                            <option value="Pending">Pending</option>
                                            <option value="Unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="jenis_produk_filter">Jenis Produk</label>
                                        <select id="jenis_produk_filter" name="jenis_produk" class="form-select"
                                            aria-label="Default select example">
                                            <option selected value="Semua">Semua</option>
                                            <option value="Internet">Internet</option>
                                            <option value="Telepon">Telepon</option>
                                            <option value="Wifi Manage Service">Wifi Manage Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('existing-adminbillper.index') }}" class="btn btn-grey">
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
                    <a href="{{ route('download.excelexistingadminbillper') }}" class="btn btn-green">
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
                                <form id="downloadForm" action="{{ route('download.filtered.excelexistingadminbillper') }}"
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
                                            <select id="status_pembayaran_download" name="status_pembayaran"
                                                class="form-select" aria-label="Default select example" required>
                                                <option selected value="Semua">Semua</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Unpaid">Unpaid</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="jenis_produk_download">Jenis Produk</label>
                                            <select id="jenis_produk_download" name="jenis_produk" class="form-select"
                                                aria-label="Default select example">
                                                <option selected value="Semua">Semua</option>
                                                <option value="Internet">Internet</option>
                                                <option value="Telepon">Telepon</option>
                                                <option value="Wifi Manage Service">Wifi Manage Service</option>
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

        {{-- Plotting --}}
        <div class="contain-btn-plotting my-3 d-flex justify-content-center justify-content-md-start">
            <button class="btn btn-secondary" id="btn-plotting">
                <i class="bi bi-person-fill-check"></i> Plotting Sales
            </button>
            <div class="w-25 ms-2">
                <select class="form-select" id="select-sales" aria-label="Default select example"
                    style="display: none;">
                    <option selected disabled>Pilih Sales</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-center justify-content-md-start my-3">
            <button class="btn btn-green" id="save-plotting" style="display: none;">
                Simpan
            </button>
        </div>

        <span class="fw-bold fst-italic">* Last Update: {{ $lastUpdate }}</span>

        <!-- DataTable -->
        <table class="table table-hover table-bordered datatable shadow" id="tabelexistingsadminbillper"
            style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle text-center">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">NPER</th>
                    <th id="th" class="align-middle text-center">Produk</th>
                    <th id="th" class="align-middle text-center">Alamat</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Sales</th>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var dataTable = new DataTable('#tabelexistingsadminbillper', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelexistingsadminbillper') }}",
                    type: 'GET',
                    data: function(d) {
                        d.nper = $('#nper_filter').val();
                        d.status_pembayaran = $('#status_pembayaran_filter').val();
                        d.jenis_produk = $('#jenis_produk_filter').val();
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
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle',
                        visible: false, // Initially hidden
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="row-checkbox" value="' + row.id +
                                '">';
                        }
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'no_inet',
                        name: 'no_inet',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'sto',
                        name: 'sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'nper',
                        name: 'nper',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'produk',
                        name: 'produk',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
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
                                return '<span class="badge text-bg-secondary">Pending</span>';
                            } else if (data === 'Paid') {
                                return '<span class="badge text-bg-success">Paid</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'nama_user',
                        name: 'nama_user',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'opsi-tabel-dataexistingadminbillper',
                        name: 'opsi-tabel-dataexistingadminbillper',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [5, 'desc']
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
            });

            // Event handler untuk select all
            $('#tabelexistingsadminbillper').on('change', '#select-all', function() {
                var isChecked = $(this).is(':checked');
                $('.row-checkbox').prop('checked', isChecked);
            });

            $('#btn-filter').on('click', function() {
                var nper = $('#nper_filter').val();
                var statusPembayaran = $('#status_pembayaran_filter').val();
                var jenisProduk = $('#jenis_produk_filter').val();

                var infoText = nper + " - " + statusPembayaran + " - " + jenisProduk;
                $('#info-filter').text(infoText);

                dataTable.ajax.reload();
            });

            // Plotting
            $('#btn-plotting').on('click', function() {
                var column = dataTable.column(0); // Get the column with index 0 (checkbox column)
                column.visible(!column.visible()); // Toggle visibility
                $('#select-sales').toggle();
                $('#save-plotting').toggle();
            });

            // Handle save button click
            $('#save-plotting').on('click', function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                var selectedSales = $('#select-sales').val();
                if (selectedIds.length > 0 && selectedSales) {
                    $.ajax({
                        url: "{{ route('savePlottingexisting') }}",
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            user_id: selectedSales,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#831a16',
                                cancelButtonColor: '#727375',
                            }).then(() => {
                                dataTable.ajax.reload();
                                var column = dataTable.column(0);
                                column.visible(false); // Hide the checkbox column
                                $('#select-sales').hide();
                                $('#save-plotting').hide();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan. Silakan coba lagi.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#831a16',
                                cancelButtonColor: '#727375',
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih data dan sales terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#831a16',
                        cancelButtonColor: '#727375',
                    });
                }
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
