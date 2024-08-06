@extends('layouts.app-super-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0 d-block d-md-none">
                Data Master
            </span>
            <span class="d-none d-md-block">
                {{-- Diver --}}
            </span>

            <div class="d-flex">
                <!-- Button trigger modal tambah pelanggan -->
                <button type="button" class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#pelanggan">
                    <i class="bi bi-plus-circle"></i> Pelanggan
                </button>

                <!-- Modal -->
                <div class="modal fade" id="pelanggan" tabindex="-1" aria-labelledby="pelangganLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="pelangganLabel">Tambah Pelanggan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('tambah-pelanggan') }}" method="POST" enctype="multipart/form-data"
                                id="tambahPelangganForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload File Data Master</label>
                                        <input class="form-control" type="file" id="formFile" name="file" required>
                                        <div id="filecekdatamaster" class="fw-bold fst-italic"></div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" id="checkFileDataMaster" class="btn btn-yellow d-none">
                                                <i class="bi bi-file-earmark-break-fill"></i> Cek File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grey" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-secondary" id="cekDataMasterButton"
                                        data-bs-dismiss="modal" disabled>Tambah Pelanggan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- BTN download Template --}}
                <a href="{{ asset('storage/file_template/Template_Tambah_Pelanggan.xlsx') }}" class="btn btn-green"
                    download>
                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Template
                </a>
            </div>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabeldatamaster" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">id</th>
                    <th id="th" class="align-middle w-25">Pelanggan</th>
                    <th id="th" class="align-middle text-centertext-center">Event Source</th>
                    <th id="th" class="align-middle text-center">CSTO</th>
                    <th id="th" class="align-middle text-center">Mobile Contact Tel</th>
                    <th id="th" class="align-middle">Email Address</th>
                    <th id="th" class="align-middle w-25">Alamat Pelanggan</th>
                    <th id="th" class="align-middle text-center">Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script type="module">
        $(document).ready(function() {
            var dataTable = new DataTable('#tabeldatamaster', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabeldatamaster') }}",
                    type: 'GET',
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
                        data: 'id',
                        name: 'id',
                        visible: false,
                        className: 'align-middle'
                    },
                    {
                        data: 'pelanggan',
                        name: 'pelanggan',
                        className: 'align-middle'
                    },
                    {
                        data: 'event_source',
                        name: 'event_source',
                        className: 'align-middle'
                    },
                    {
                        data: 'csto',
                        name: 'csto',
                        className: 'align-middle'
                    },
                    {
                        data: 'mobile_contact_tel',
                        name: 'mobile_contact_tel',
                        className: 'align-middle'
                    },
                    {
                        data: 'email_address',
                        name: 'email_address',
                        className: 'align-middle'
                    },
                    {
                        data: 'alamat_pelanggan',
                        name: 'alamat_pelanggan',
                        className: 'align-middle'
                    },
                    {
                        data: 'opsi-tabel-datamaster',
                        name: 'opsi-tabel-datamaster',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [
                    [25, 50, 100],
                    [25, 50, 100]
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
        });

        document.getElementById('formFile').addEventListener('change', function() {
            document.getElementById('checkFileDataMaster').classList.remove('d-none');
        });

        document.getElementById('checkFileDataMaster').addEventListener('click', function() {
            let formData = new FormData();
            formData.append('file', document.getElementById('formFile').files[0]);

            let fileStatusElement = document.getElementById('filecekdatamaster');
            fileStatusElement.innerText = '';
            fileStatusElement.classList.remove('text-success', 'text-danger');

            let checkFileButton = document.getElementById('checkFileDataMaster');
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

            fetch('{{ route('cek.filedatamaster') }}', {
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
                        document.getElementById('cekDataMasterButton').disabled = false;
                    } else {
                        fileStatusElement.classList.add('text-danger');
                        document.getElementById('cekDataMasterButton').disabled = true;
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

        // Event listener untuk menampilkan loading screen saat form disubmit
        document.getElementById('tambahPelangganForm').addEventListener('submit', function(event) {
            document.getElementById('loadingScreen').classList.remove('d-none');
        });

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
