@extends('layouts.app-user')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
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
                responsive: true,
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
                        data: 'alls.nama',
                        name: 'alls.nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'alls.no_inet',
                        name: 'alls.no_inet',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.saldo',
                        name: 'alls.saldo',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'alls.no_tlf',
                        name: 'alls.no_tlf',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.email',
                        name: 'alls.email',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.sto',
                        name: 'alls.sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.nper',
                        name: 'alls.nper',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.umur_customer',
                        name: 'alls.umur_customer',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'alls.produk',
                        name: 'alls.produk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'alls.status_pembayaran',
                        name: 'alls.status_pembayaran',
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
                    lengthMenu: "Tampilkan _MENU_ data"
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
    </script>
@endpush
