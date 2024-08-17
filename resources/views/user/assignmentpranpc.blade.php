@extends('layouts.app-user')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">Assignment Pranpc</span>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelassignmentpranpc" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">No Telfon</th>
                    <th id="th" class="align-middle text-center">Email</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">Mintgk</th>
                    <th id="th" class="align-middle text-center">Maxtgk</th>
                    <th id="th" class="align-middle text-center">Bill Bln</th>
                    <th id="th" class="align-middle text-center">Bill Bln1</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            var dataTable = new DataTable('#tabelassignmentpranpc', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: {
                    details: {
                        type: 'column',
                        target: 'td:first-child', // Targets the first column for the collapse icon
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                    col.columnIndex + '">' +
                                    '<td><strong>' + col.title + ' </strong></td>' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table/>').append(data) : false;
                        }
                    }
                },
                ajax: {
                    url: "{{ route('gettabelassignmentpranpc') }}",
                    type: 'GET',
                    beforeSend: function() {
                        $('#loadingScreen').removeClass('d-none');
                    },
                    complete: function() {
                        $('#loadingScreen').addClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
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
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'bill_bln1',
                        name: 'bill_bln1',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran',
                        className: 'align-middle text-center',
                        render: function(data) {
                            if (data === 'Unpaid Visit 1') {
                                return '<span class="badge text-bg-danger">Unpaid Visit 1</span>';
                            } else if (data === 'Unpaid') {
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
                        data: 'opsi-tabel-assignmentpranpc',
                        name: 'opsi-tabel-assignmentpranpc',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [6, 'asc']
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
        });
    </script>
@endpush
