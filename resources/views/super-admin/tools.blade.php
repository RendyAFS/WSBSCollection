@extends('layouts.app-super-admin')

@include('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Tools
            </span>
        </div>

        <form id="uploadForm" action="{{ route('vlookup.perform') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('tools.index') }}" class="text-danger fw-bold link-underline link-underline-opacity-0">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                </div>
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class="card w-100 shadow-sm px-2 py-3">
                        <div class="card-body">
                            <div class="header-card mb-3">
                                <h5 class="card-title">Upload File</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">SND</h6>
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
                <div class="col-12 col-md-6">
                    <div class="card w-100 shadow-sm px-2 py-3">
                        <div class="card-body">
                            <div class="header-card mb-3">
                                <h5 class="card-title">Upload File</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">EVENT_SOURCE</h6>
                            </div>
                            <input class="form-control" type="file" name="file2" id="file2" required>
                            <div id="file2Status" class="fw-bold fst-italic"></div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" id="checkFile2" class="btn btn-yellow d-none">
                                    <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-secondary mt-3 w-50" id="vlookupBtn" disabled>
                        <i class="bi bi-intersect"></i> Vlookup Data
                    </button>
                </div>
            </div>
        </form>


        <div class="mb-4 pt-4">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
                <span class="fw-bold fs-2 mb-3 mb-md-0">
                    Preview data
                </span>
                <div class="contain-btn-save">
                    @if ($temp_alls->isEmpty())
                        {{-- None --}}
                    @else
                        <form action="{{ route('savealls') }}" method="POST" style="display:inline;">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="bulan_tahun">Bulan/Tahun</label>
                                <input type="month" id="bulan_tahun" name="bulan_tahun" class="form-control" required>
                            </div>

                            <!-- Tambahkan input lainnya sesuai kebutuhan -->
                            <button type="submit" class="btn btn-secondary btn-save" id="btn-save">
                                <i class="bi bi-floppy2-fill"></i> Simpan
                            </button>
                        </form>
                        <form action="{{ route('deleteAllTempalls') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-delete-all">
                                <i class="bi bi-trash-fill"></i> Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <table class="table table-hover table-bordered datatable shadow" id="tabeltempalls" style="width: 100%">
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
        // DataTable initialization
        $(document).ready(function() {
            var dataTable = new DataTable('#tabeltempalls', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabeltempalls') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'nama',
                        name: 'nama'
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
                        data: 'opsi-tabel-datatempall',
                        name: 'opsi-tabel-datatempall',
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
                    const bulanTahun = form.querySelector('#bulan_tahun').value;

                    if (!bulanTahun) {
                        Swal.fire({
                            title: "Error!",
                            text: "Harap isi Bulan/Tahun terlebih dahulu!",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    } else {
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
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = 'bulan_tahun';
                                hiddenInput.value = bulanTahun;
                                form.appendChild(hiddenInput);

                                form.submit();
                            }
                        });
                    }
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
                        window.location.href = '{{ route('tools.index') }}';
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

        // Check file 1
        document.getElementById('checkFile1').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file1', document.getElementById('file1').files[0]);

            let file1StatusElement = document.getElementById('file1Status');
            file1StatusElement.innerText = '';
            file1StatusElement.classList.remove('text-success', 'text-danger');

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

            fetch('{{ route('vlookup.checkFile1') }}', {
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
                    checkFileButton.disabled = false;

                    if (data.status === 'success') {
                        file1StatusElement.classList.add('text-success');
                        checkFileButton.disabled = true;
                        enableVlookupButton();
                    } else {
                        file1StatusElement.classList.add('text-danger');
                    }
                });
        });

        // Check file 2
        document.getElementById('checkFile2').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file2', document.getElementById('file2').files[0]);

            let file2StatusElement = document.getElementById('file2Status');
            file2StatusElement.innerText = '';
            file2StatusElement.classList.remove('text-success', 'text-danger');

            let checkFileButton = document.getElementById('checkFile2');
            checkFileButton.classList.add('d-none');
            let loadingElement = document.createElement('div');
            loadingElement.classList.add('loading2', 'd-block');
            loadingElement.innerHTML = `
                <div class="spinner-border text-dark" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Proses
            `;
            checkFileButton.parentElement.appendChild(loadingElement);

            fetch('{{ route('vlookup.checkFile2') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    file2StatusElement.innerText = data.message;
                    file2StatusElement.classList.remove('text-success', 'text-danger');

                    loadingElement.remove();
                    checkFileButton.classList.remove('d-none');
                    checkFileButton.disabled = false;

                    if (data.status === 'success') {
                        file2StatusElement.classList.add('text-success');
                        checkFileButton.disabled = true;
                        enableVlookupButton();
                    } else {
                        file2StatusElement.classList.add('text-danger');
                    }
                });
        });

        // Show "Check File" button if file selected
        document.getElementById('file1').addEventListener('change', function() {
            let fileInput = document.getElementById('file1');
            let checkFileButton = document.getElementById('checkFile1');

            if (fileInput.files.length > 0) {
                checkFileButton.classList.remove('d-none');
                checkFileButton.classList.add('d-block');
            } else {
                checkFileButton.classList.remove('d-block');
                checkFileButton.classList.add('d-none');
            }
        });

        document.getElementById('file2').addEventListener('change', function() {
            let fileInput = document.getElementById('file2');
            let checkFileButton = document.getElementById('checkFile2');

            if (fileInput.files.length > 0) {
                checkFileButton.classList.remove('d-none');
                checkFileButton.classList.add('d-block');
            } else {
                checkFileButton.classList.remove('d-block');
                checkFileButton.classList.add('d-none');
            }
        });

        // Enable Vlookup button
        function enableVlookupButton() {
            if (document.getElementById('checkFile1').disabled && document.getElementById('checkFile2').disabled) {
                document.getElementById('vlookupBtn').disabled = false;
            }
        }
    </script>
@endpush
