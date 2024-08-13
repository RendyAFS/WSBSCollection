@extends('layouts.app-super-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                <span class="d-block d-md-none">
                    Data Existing
                </span>
                <span id="info-filter-head">

                </span>
                <span id="info-filter" class="fs-6 fw-normal">

                </span>
            </span>

            <div class="d-flex flex-column flex-lg-row">
                <!-- Button trigger modal Filter Data-->
                <button type="button" class="btn btn-white me-2 mb-2 mb-xl-0" data-bs-toggle="modal" data-bs-target="#modalFilterdata">
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
                            <form id="filterForm" action="{{ route('existing.index') }}" method="POST">
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
                                    <a href="{{ route('existing.index') }}" class="btn btn-grey">
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
                <a class="btn btn-white me-2 mb-2 mb-xl-0" href="{{ route('existingriwayat.index') }}" role="button"><i
                        class="bi bi-clock-fill"></i> Riwayat</a>

                <!-- Button trigger modal Pembayaran-->
                <button type="button" class="btn btn-secondary me-2 mb-2 mb-xl-0" data-bs-toggle="modal"
                    data-bs-target="#modalCekPembayaran">
                    <i class="bi bi-capslock-fill"></i> Update Pembayaran
                </button>

                <!-- Modal -->
                <div class="modal fade" id="modalCekPembayaran" tabindex="-1" aria-labelledby="modalCekPembayaranLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalCekPembayaranLabel">Cek Pembayaran</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="cekPembayaranForm" action="{{ route('cek-pembayaranexisting') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="nper">Pilih Bulan-Tahun</label>
                                        <input type="month" id="nper" name="nper" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload File SND</label>
                                        <input class="form-control" type="file" id="formFile" name="file"
                                            required>
                                        <div id="filecekpembayaran" class="fw-bold fst-italic"></div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" id="checkFilePembayaran"
                                                class="btn btn-yellow d-none">
                                                <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grey" data-bs-dismiss="modalpx-5">Batal</button>
                                    <button type="submit" class="btn btn-secondary" id="cekPembayaranButton"
                                        disabled>Cek Pembayaran</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                {{-- BTN Donwload --}}
                <div class="btn-group">
                    <a href="{{ route('download.excelexistingsuperadmin') }}" class="btn btn-green">
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
                                <form id="downloadForm" action="{{ route('download.filtered.excelexistingsuperadmin') }}"
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

        <span class="fw-bold fst-italic">* Last Update: {{ $lastUpdate }}</span>

        <table class="table table-hover table-bordered datatable shadow" id="tabelalls" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">No. Tlf</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">NPER</th>
                    <th id="th" class="align-middle text-center">Umur Customer</th>
                    <th id="th" class="align-middle text-center">Produk</th>
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
            var dataTable = new DataTable('#tabelalls', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelexistings') }}",
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
                        data: 'no_tlf',
                        name: 'no_tlf',
                        className: 'align-middle text-center'
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
                        data: 'umur_customer',
                        name: 'umur_customer',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'produk',
                        name: 'produk',
                        className: 'align-middle text-center',
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
                        data: 'opsi-tabel-dataexisting',
                        name: 'opsi-tabel-dataexisting',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [5, 'desc']
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
                var nper = $('#nper_filter').val();
                var statusPembayaran = $('#status_pembayaran_filter').val();

                var infoText = nper + " - " + statusPembayaran;
                $('#info-filter').text(infoText);

                dataTable.ajax.reload();
            });
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

        // Check file pembayaran
        document.getElementById('formFile').addEventListener('change', function() {
            document.getElementById('checkFilePembayaran').classList.remove('d-none');
        });

        document.getElementById('checkFilePembayaran').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file', document.getElementById('formFile').files[0]);

            let fileStatusElement = document.getElementById('filecekpembayaran');
            fileStatusElement.innerText = '';
            fileStatusElement.classList.remove('text-success', 'text-danger');

            let checkFileButton = document.getElementById('checkFilePembayaran');
            checkFileButton.classList.add('d-none');
            let loadingElement = document.createElement('div');
            loadingElement.classList.add('loading', 'd-block');
            loadingElement.innerHTML = `
        <div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Proses
    `;
            checkFileButton.parentElement.appendChild(loadingElement);

            fetch('{{ route('cek.filepembayaranexisting') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    fileStatusElement.innerText = data.message;
                    fileStatusElement.classList.remove('text-success', 'text-danger');

                    loadingElement.remove();
                    checkFileButton.classList.remove('d-none');
                    checkFileButton.disabled = false;

                    if (data.status === 'success') {
                        fileStatusElement.classList.add('text-success');
                        document.getElementById('cekPembayaranButton').disabled = false;
                    } else {
                        fileStatusElement.classList.add('text-danger');
                        // Menonaktifkan tombol Cek Pembayaran jika file tidak sesuai
                        document.getElementById('cekPembayaranButton').disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    fileStatusElement.innerText = 'An error occurred. Please try again.';
                    fileStatusElement.classList.add('text-danger');
                    loadingElement.remove();
                    checkFileButton.classList.remove('d-none');
                });
        });

        document.getElementById('cekPembayaranForm').addEventListener('submit', function(event) {
            let submitButton = document.getElementById('cekPembayaranButton');
            submitButton.disabled = true;
            submitButton.innerHTML = `
        <div class="spinner-border text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Proses
    `;
        });



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
    </script>
@endpush
