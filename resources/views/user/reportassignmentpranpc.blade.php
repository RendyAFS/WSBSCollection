@extends('layouts.app-user')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Report Assignment Pranpc
            </span>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelreportassignmentpranpc" style="width: 100%">
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
            var dataTable = new DataTable('#tabelreportassignmentpranpc', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelreportassignmentpranpc') }}",
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
                        data: 'pranpcs.nama',
                        name: 'pranpcs.nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'pranpcs.snd',
                        name: 'pranpcs.snd',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'pranpcs.multi_kontak1',
                        name: 'pranpcs.multi_kontak1',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'pranpcs.email',
                        name: 'pranpcs.email',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'pranpcs.sto',
                        name: 'pranpcs.sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'pranpcs.mintgk',
                        name: 'pranpcs.mintgk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'pranpcs.maxtgk',
                        name: 'pranpcs.maxtgk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'pranpcs.bill_bln',
                        name: 'pranpcs.bill_bln',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'pranpcs.bill_bln1',
                        name: 'pranpcs.bill_bln1',
                        className: 'align-middle text-center',
                        render: function(data) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'pranpcs.status_pembayaran',
                        name: 'pranpcs.status_pembayaran',
                        className: 'align-middle text-center',
                        render: function(data) {
                            if (data === 'Unpaid')
                                return '<span class="badge text-bg-warning">Unpaid</span>';
                            if (data === 'Pending')
                                return '<span class="badge text-bg-secondary">Pending</span>';
                            if (data === 'Paid')
                                return '<span class="badge text-bg-success">Paid</span>';
                            return data;
                        }
                    },
                    {
                        data: 'jmlh_visit',
                        name: 'jmlh_visit',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'waktu_visit',
                        name: 'waktu_visit',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'opsi-tabel-reportassignmentpranpc',
                        name: 'opsi-tabel-reportassignmentpranpc',
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
