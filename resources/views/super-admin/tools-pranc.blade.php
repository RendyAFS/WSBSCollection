@extends('layouts.app-super-admin')

@include('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Tools
            </span>
        </div>

        <form id="uploadForm" action="{{ route('upload.perform') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="card w-100 shadow-sm px-2 py-3">
                        <div class="card-body">
                            <div class="header-card mb-3">
                                <h5 class="card-title">Upload File</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">PRANPC</h6>
                            </div>
                            <input class="form-control" type="file" name="file1" id="file1" required>
                            <div id="file1Status" class="fw-bold fst-italic"></div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" id="checkFile1" class="btn btn-yellow d-none">
                                    <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="header-desc">
                        <div class="d-flex justify-content-between">
                            <span class="fs-5 fw-bold mb-3">Deskripsi File</span>
                            <a href="{{ route('toolspranpc.index') }}" id="resetLink"
                                class="text-danger fw-bold link-underline link-underline-opacity-0 d-none">
                                <i class="bi bi-x-lg"></i> Reset
                            </a>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-secondary text-center mt-2" id="fileNotUploaded">File Belum di Upload!</span>
                            <span class="text-secondary d-none" id="fileNameLabel">Nama File</span>
                            <span class="text-secondary d-none mb-3" id="fileSizeLabel">Ukuran File</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary mt-3 w-50" id="uploadBtn" disabled>
                        <i class="bi bi-upload"></i> Upload Data
                    </button>
                </div>
            </div>
        </form>

        <div class="mb-4 pt-4">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
                <span class="fw-bold fs-2 mb-3 mb-md-0">
                    Preview data
                </span>
                <div class="contain-btn-save d-flex">
                    @if ($temp_pranpcs->isEmpty())
                        {{-- None --}}
                    @else
                        <form action="{{ route('savepranpcs') }}" method="POST">
                            @csrf
                            <!-- Tambahkan input lainnya sesuai kebutuhan -->
                            <button type="submit" class="btn btn-green btn-save me-2" id="btn-save">
                                <i class="bi bi-floppy2-fill"></i> Simpan
                            </button>
                        </form>

                        <form action="{{ route('deleteAllTemppranpcs') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-delete-all">
                                <i class="bi bi-trash-fill"></i> Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <table class="table table-hover table-bordered datatable shadow" id="tabeltemppranpcs" style="width: 100%">
                <thead class="fw-bold">
                    <tr>
                        <th id="th" class="align-middle">Nama</th>
                        <th id="th" class="align-middle text-center">No. Inet</th>
                        <th id="th" class="align-middle text-center">STO</th>
                        <th id="th" class="align-middle text-center">Bill Bln</th>
                        <th id="th" class="align-middle text-center">Bill Bln1</th>
                        <th id="th" class="align-middle text-center">No Hp</th>
                        <th id="th" class="align-middle">Email</th>
                        <th id="th" class="align-middle text-center">Alamat</th>
                        <th id="th" class="align-middle text-center">Opsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // DataTable initialization
        $(document).ready(function() {
            var dataTable = new DataTable('#tabeltemppranpcs', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabeltemppranpcs') }}",
                    type: 'GET',
                    beforeSend: function() {
                        // Tampilkan loading screen sebelum ajax request
                        $('#loadingScreen').removeClass('d-none');
                    },
                    complete: function() {
                        // Sembunyikan loading screen setelah ajax request selesai
                        $('#loadingScreen').addClass('d-none');
                    },
                    error: function() {
                        // Sembunyikan loading screen jika terjadi error pada ajax request
                        $('#loadingScreen').addClass('d-none');
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama',
                        className: 'align-middle',
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
                        data: 'alamat',
                        name: 'alamat',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'opsi-tabel-datatemppranpc',
                        name: 'opsi-tabel-datatemppranpc',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
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
                confirmButtonClass: "bg-primary",
                confirmButtonText: "Ya, hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Modal save confirmation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-save').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const form = this.closest('form');

                    Swal.fire({
                        title: "Simpan Data?",
                        text: "Anda yakin ingin menyimpan data ini?",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "Ya, simpan!",
                        cancelButtonText: "Batal",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });


        // Modal delete all confirmation
        document.querySelectorAll('.btn-delete-all').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const form = this.closest('form');

                Swal.fire({
                    title: "Apakah Anda Yakin Menghapus Semua Data?",
                    text: "Anda tidak akan dapat mengembalikannya!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, hapus semua!",
                    cancelButtonText: "Batal",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show loading screen on form submit
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            document.getElementById('loadingScreen').classList.remove('d-none');
        });

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            showLoading();

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '{{ route('toolspranpc.index') }}';
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    hideLoading();
                });

            event.preventDefault();
        });

        function showLoading() {
            document.getElementById('loadingScreen').classList.remove('d-none');
        }

        function hideLoading() {
            document.getElementById('loadingScreen').classList.add('d-none');
        }

        // Check file 1 dan Upload
        document.getElementById('checkFile1').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file1', document.getElementById('file1').files[0]);

            let file1StatusElement = document.getElementById('file1Status');
            file1StatusElement.innerText = '';
            file1StatusElement.classList.remove('text-success', 'text-danger');

            let uploadButton = document.getElementById('uploadBtn');

            let checkFileButton = document.getElementById('checkFile1');
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

            fetch('{{ route('upload.checkFile1pranpc') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    file1StatusElement.innerText = data.message;
                    file1StatusElement.classList.remove('text-success', 'text-danger');

                    loadingElement.remove();
                    checkFileButton.classList.remove('d-none');

                    if (data.status === 'success') {
                        file1StatusElement.classList.add('text-success');
                        checkFileButton.disabled = true;
                        uploadButton.disabled = false;
                    } else {
                        file1StatusElement.classList.add('text-danger');
                        checkFileButton.disabled = false;
                        uploadButton.disabled = true;
                    }
                });
        });

        // Deskripsi File
        document.getElementById('file1').addEventListener('change', function() {
            let file1 = document.getElementById('file1').files[0];
            let checkFileButton = document.getElementById('checkFile1');
            let fileNotUploaded = document.getElementById('fileNotUploaded');
            let fileNameLabel = document.getElementById('fileNameLabel');
            let fileSizeLabel = document.getElementById('fileSizeLabel');
            let resetLink = document.getElementById('resetLink');
            let file1StatusElement = document.getElementById('file1Status');
            let uploadButton = document.getElementById('uploadBtn'); // Tambahkan ini

            if (file1) {
                fileNotUploaded.classList.add('d-none');
                fileNameLabel.classList.remove('d-none');
                fileSizeLabel.classList.remove('d-none');
                fileNameLabel.innerText = `Nama File: ${file1.name}`;
                if (file1.size > 1024 * 1024) {
                    fileSizeLabel.innerText = `Ukuran File: ${(file1.size / (1024 * 1024)).toFixed(2)} MB`;
                } else {
                    fileSizeLabel.innerText = `Ukuran File: ${(file1.size / 1024).toFixed(2)} KB`;
                }
                checkFileButton.classList.remove('d-none');
                checkFileButton.disabled = false;
                resetLink.classList.remove('d-none');

                // Mengosongkan konten file1Status
                file1StatusElement.innerText = '';
                file1StatusElement.classList.remove('text-success', 'text-danger');

                // Tambahkan ini untuk menonaktifkan tombol upload
                uploadButton.disabled = true;
            } else {
                fileNotUploaded.classList.remove('d-none');
                fileNameLabel.classList.add('d-none');
                fileSizeLabel.classList.add('d-none');
                checkFileButton.classList.add('d-none');
                resetLink.classList.add('d-none');

                // Mengosongkan konten file1Status
                file1StatusElement.innerText = '';
                file1StatusElement.classList.remove('text-success', 'text-danger');

                // Tambahkan ini untuk menonaktifkan tombol upload
                uploadButton.disabled = true;
            }
        });
    </script>
@endpush
