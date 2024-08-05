@extends('layouts.app-super-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3 d-block d-md-none">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                <div class="spinner-grow text-warning shadow" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Preview Data Master
            </span>

            <div class="d-flex">

            </div>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-end mb-3">
            <div class="contain-btn-save d-flex">
                <form action="{{ route('savedatamasters') }}" method="POST">
                    @csrf
                    <!-- Tambahkan input lainnya sesuai kebutuhan -->
                    <button type="submit" class="btn btn-green btn-save me-2" id="btn-save">
                        <i class="bi bi-floppy2-fill"></i> Simpan
                    </button>
                </form>

                <form action="{{ route('deleteAllTempdatamasters') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-delete-all">
                        <i class="bi bi-trash-fill"></i> Hapus Semua
                    </button>
                </form>
            </div>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelpreviewdatamaster" style="width: 100%">
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
            var dataTable = new DataTable('#tabelpreviewdatamaster', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelpreviewdatamaster') }}",
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
                        data: 'opsi-tabel-previewdatamaster',
                        name: 'opsi-tabel-previewdatamaster',
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
                    confirmButtonColor: '#831a16',
                    cancelButtonColor: '#727375',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
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
                        confirmButtonColor: '#831a16',
                        cancelButtonColor: '#727375',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
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
