@extends('layouts.app-user')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">
                Report Assignment Billper
            </span>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelreportassignmentbillper" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">No. Tlf</th>
                    <th id="th" class="align-middle">Email</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">NPER</th>
                    <th id="th" class="align-middle text-center">Umur Customer</th>
                    <th id="th" class="align-middle text-center">Produk</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Visit</th>
                    <th id="th" class="align-middle text-center">Waktu Visit</th>
                    <th id="th" class="align-middle text-center">Opsi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script type="module">
        $(document).ready(function() {
            var dataTable = new DataTable('#tabelreportassignmentbillper', {
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
                    url: "{{ route('gettabelreportassignmentbillper') }}",
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
                        data: 'billpers.nama',
                        name: 'billpers.nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'billpers.no_inet',
                        name: 'billpers.no_inet',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.saldo',
                        name: 'billpers.saldo',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'billpers.no_tlf',
                        name: 'billpers.no_tlf',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.email',
                        name: 'billpers.email',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.sto',
                        name: 'billpers.sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.nper',
                        name: 'billpers.nper',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.umur_customer',
                        name: 'billpers.umur_customer',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'billpers.produk',
                        name: 'billpers.produk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'billpers.status_pembayaran',
                        name: 'billpers.status_pembayaran',
                        className: 'align-middle text-center',
                        render: function(data) {
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
                        data: 'jmlh_visit',
                        name: 'jmlh_visit',
                        className: 'align-middle text-center',
                    },
                    {
                        data: 'waktu_visit',
                        name: 'waktu_visit',
                        className: 'align-middle text-center',
                    },
                    {
                        data: 'opsi-tabel-reportassignmentbillper',
                        name: 'opsi-tabel-reportassignmentbillper',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [7, 'asc']
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
    </script>
@endpush
