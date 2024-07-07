@extends('layouts.app-super-admin')

<div class="contain-loading d-none" id="loadingScreen">
    <div class="content-loading">
        <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
            <img src="{{ Vite::asset('resources/images/logo-telkom.png') }}" alt="" id="img-loading">
            <span class="ms-2 fs-4 fw-bold">
                Proses Vlook Up Data
            </span>
        </div>
    </div>
</div>

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
                    <a href="{{ route('tools.index') }}"
                        class="text-danger fw-bold link-underline link-underline-opacity-0">
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
            <span class="fw-bold fs-2">
                Preview data
            </span>

            <table class="table table-hover table-bordered datatable shadow" id="tabelpranpcs" style="width: 100%">
                <thead class="fw-bold">
                    <tr>
                        <th id = "th" class="w-20">Nama</th>
                        <th id = "th" class="w-15">No. Inet</th>
                        <th id = "th" class="w-10">Saldo</th>
                        <th id = "th" class="w-15">No. Tlf</th>
                        <th id = "th" class="w-20">Email</th>
                        <th id = "th" class="w-10">STO</th>
                        <th id = "th" class="w-10">Umur Customer</th>
                        <th id = "th" class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($temp_pranpcs as $data)
                        <tr data-id="{{ $data->id }}">
                            <td>
                                <span class="fw-normal">{{ $data->nama }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->no_inet }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->saldo }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->no_tlf }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->email }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->sto }}</span>
                            </td>
                            <td>
                                <span class="fw-normal">{{ $data->umur_customer }}</span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('destroy-pranpcs', ['id' => $data->id]) }}" method="POST"
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
            new DataTable('#tabelpranpcs', {
                responsive: true
            });
            $('.form-select').change(function() {
                var status = $(this).val();
                var userId = $(this).closest('tr').data('id');

                $.ajax({
                    url: '{{ route('updatestatus') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId,
                        status: status
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Status Memperbaharui',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function(response) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to update status',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
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
                .then(response => response.blob())
                .then(blob => {
                    // Create a link element to download the file
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'result.xlsx';
                    link.click();

                    // Hide the loading screen
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);

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
