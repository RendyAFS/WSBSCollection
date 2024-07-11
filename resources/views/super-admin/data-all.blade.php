@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Data All
            </span>

            <div class="d-flex">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-arrow-repeat"></i> Cek Pembayaran
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Pembayaran</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('cek-pembayaran') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="bulan_tahun">Pilih Bulan-Tahun</label>
                                        <input type="month" id="bulan_tahun" name="bulan_tahun" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload File SND</label>
                                        <input class="form-control" type="file" id="formFile" name="file" required>
                                        <div id="filecekpembayaran" class="fw-bold fst-italic"></div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" id="checkFilePembayaran" class="btn btn-yellow d-none">
                                                <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grey" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-secondary" id="cekPembayaranButton" disabled>Cek
                                        Pembayaran</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ route('download.excel') }}" class="btn btn-green">
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
                                <form id="downloadForm" action="{{ route('download.filtered.excel') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="bulan_tahun">Pilih Bulan-Tahun</label>
                                            <input type="month" id="bulan_tahun_download" name="bulan_tahun"
                                                class="form-control" required>
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

        <table class="table table-hover table-bordered datatable shadow" id="tabelalls" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">No. Tlf</th>
                    <th id="th" class="align-middle">Email</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">Umur Customer</th>
                    <th id="th" class="align-middle text-center">Produk</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Tahun-Bulan</th>
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
                    url: "{{ route('gettabelalls') }}",
                    type: 'GET',
                },
                columns: [{
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
                        className: 'align-middle'
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
                        data: 'umur_customer',
                        name: 'umur_customer',
                        className: 'align-middle'
                    },
                    {
                        data: 'produk',
                        name: 'produk',
                        className: 'align-middle'
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran',
                        className: 'align-middle'
                    },
                    {
                        data: 'bulan_tahun',
                        name: 'bulan_tahun',
                        visible: false,
                        className: 'align-middle'
                    },
                    {
                        data: 'opsi-tabel-dataall',
                        name: 'opsi-tabel-dataall',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
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
        });

        // Validate filter download
        document.addEventListener('DOMContentLoaded', function() {
            const btnSave = document.getElementById('btn-filter-download');
            const bulanTahunInput = document.getElementById('bulan_tahun_download');

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

            fetch('{{ route('cek.filepembayaran') }}', {
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


        // Modal delete confirmation
        $(".datatable").on("click", ".btn-delete", function(e) {
            e.preventDefault();

            var form = $(this).closest("form");

            Swal.fire({
                title: "Apakah Anda Yakin Menghapus Data " + "?",
                text: "Anda tidak akan dapat mengembalikannya!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonClass: "bg-primary",
                confirmButtonText: "Ya, hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
