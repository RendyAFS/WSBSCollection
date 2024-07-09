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
                                <button type="button" id="checkFile1" class="btn btn-warning d-none">
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
                                <button type="button" id="checkFile2" class="btn btn-warning d-none">
                                    <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-submit mt-3 w-50" id="vlookupBtn" disabled>
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
                    @if ($temp_billpers->isEmpty())
                        {{-- None --}}
                    @else
                        <form action="{{ route('savebillpers') }}" method="POST" style="display:inline;">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="bulan_tahun">Bulan/Tahun</label>
                                <input type="month" id="bulan_tahun" name="bulan_tahun" class="form-control" required>
                            </div>

                            <!-- Tambahkan input lainnya sesuai kebutuhan -->

                            <button type="submit" class="btn btn-submit btn-save" id="btn-save">
                                <i class="bi bi-floppy2-fill"></i> Simpan
                            </button>
                        </form>
                        <form action="{{ route('deleteAllTempBillpers') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-delete-all">
                                <i class="bi bi-trash-fill"></i> Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <table class="table table-hover table-bordered datatable shadow" id="tabeltempbillpers" style="width: 100%">
                <thead class="fw-bold">
                    <tr>
                        <th id = "th" class="align-middle">Nama</th>
                        <th id = "th" class="align-middle text-center">No. Inet</th>
                        <th id = "th" class="align-middle text-center">Saldo</th>
                        <th id = "th" class="align-middle text-center">No. Tlf</th>
                        <th id = "th" class="align-middle ">Email</th>
                        <th id = "th" class="align-middle text-center">STO</th>
                        <th id = "th" class="align-middle text-center">Umur Customer</th>
                        <th id = "th" class="align-middle text-center">Produk</th>
                        <th id = "th" class="align-middle text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($temp_billpers as $data)
                        <tr data-id="{{ $data->id }}">
                            <td class="align-middle ">
                                <span class="fw-normal">{{ $data->nama }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->no_inet }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->saldo }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->no_tlf }}</span>
                            </td>
                            <td class="align-middle ">
                                <span class="fw-normal">{{ $data->email }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->sto }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->umur_customer }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="fw-normal">{{ $data->produk }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <form action="{{ route('destroy-tempbillpers', ['id' => $data->id]) }}" method="POST"
                                    class="delete-form">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm me-2 btn-delete shadow">
                                        <i class="bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        // Table
        $(document).ready(function() {
            new DataTable('#tabeltempbillpers', {
                responsive: true
            });
            $('.form-select').change(function() {
                var status = $(this).val();
                var userId = $(this).closest('tr').data('id');
            });
        });

        // modal delete
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    const form = this.closest('form');

                    Swal.fire({
                        title: "Apakah Anda Yakin Menghapus Data?",
                        text: "Anda tidak akan dapat mengembalikannya!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ya, hapus!",
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

        // Script untuk modal simpan
        document.querySelectorAll('.btn-save').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const form = this.closest('form');
                const bulanTahun = form.querySelector('#bulan_tahun').value; // Ambil nilai bulan/tahun

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
                        // Tambahkan nilai bulan/tahun ke form data sebelum submit
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'bulan_tahun';
                        hiddenInput.value = bulanTahun;
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const btnSave = document.getElementById('btn-save');
            const bulanTahunInput = document.getElementById('bulan_tahun');

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


        // Script untuk modal hapus semua temp_billpers
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
            // Show the loading screen
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
                        // Redirect to another page or update UI
                        window.location.href = '{{ route('tools.index') }}'; // Redirect to tools.index
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Optionally show error message or handle error UI
                })
                .finally(() => {
                    // Hide the loading screen
                    hideLoading();
                });

            // Prevent the default form submission
            event.preventDefault();
        });



        function showLoading() {
            document.getElementById('loadingScreen').classList.remove('d-none');
        }

        function hideLoading() {
            document.getElementById('loadingScreen').classList.add('d-none');
        }

        // Cek file SND
        document.getElementById('checkFile1').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file1', document.getElementById('file1').files[0]);

            // Clear previous message
            let file1StatusElement = document.getElementById('file1Status');
            file1StatusElement.innerText = '';
            file1StatusElement.classList.remove('text-success', 'text-danger');

            // Hide the "Cek File" button and show loading indicator
            let checkFileButton = document.getElementById('checkFile1');
            checkFileButton.classList.add('d-none'); // Hide button
            let loadingElement = document.createElement('div');
            loadingElement.classList.add('loading', 'd-block'); // Show loading indicator
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

                    // Clear existing classes
                    file1StatusElement.classList.remove('text-success', 'text-danger');

                    // Remove loading indicator
                    loadingElement.remove();

                    // Show the "Cek File" button
                    checkFileButton.classList.remove('d-none');
                    checkFileButton.disabled = false;

                    // Add appropriate class based on status
                    if (data.status === 'success') {
                        file1StatusElement.classList.add('text-success');
                        checkFileButton.disabled = true;
                        enableVlookupButton();
                    } else {
                        file1StatusElement.classList.add('text-danger');
                    }
                });
        });

        // Cek file EVENT_SOURCE
        document.getElementById('checkFile2').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file2', document.getElementById('file2').files[0]);

            // Clear previous message
            let file2StatusElement = document.getElementById('file2Status');
            file2StatusElement.innerText = '';
            file2StatusElement.classList.remove('text-success', 'text-danger');

            // Hide the "Cek File" button and show loading indicator
            let checkFileButton = document.getElementById('checkFile2');
            checkFileButton.classList.add('d-none'); // Hide button
            let loadingElement = document.createElement('div');
            loadingElement.classList.add('loading2', 'd-block'); // Show loading indicator
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

                    // Clear existing classes
                    file2StatusElement.classList.remove('text-success', 'text-danger');

                    // Remove loading indicator
                    loadingElement.remove();

                    // Show the "Cek File" button
                    checkFileButton.classList.remove('d-none');
                    checkFileButton.disabled = false;

                    // Add appropriate class based on status
                    if (data.status === 'success') {
                        file2StatusElement.classList.add('text-success');
                        checkFileButton.disabled = true;
                        enableVlookupButton();
                    } else {
                        file2StatusElement.classList.add('text-danger');
                    }
                });
        });

        // Menampilkan tombol "Cek File" jika file terpilih
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

        // VlookUp Data
        function enableVlookupButton() {
            if (document.getElementById('checkFile1').disabled && document.getElementById('checkFile2').disabled) {
                document.getElementById('vlookupBtn').disabled = false;
            }
        }
    </script>
@endpush
