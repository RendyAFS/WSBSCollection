@extends('layouts.app-user')

@include('layouts.loading')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Assignment Billper
            </span>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelassignmentbillper" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">NPER</th>
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
            var dataTable = new DataTable('#tabelassignmentbillper', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelassignmentbillper') }}",
                    type: 'GET',
                    beforeSend: function() {
                        $('#loadingScreen').removeClass('d-none');
                    },
                    complete: function() {
                        $('#loadingScreen').addClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        $('#loadingScreen').addClass('d-none');
                    }
                },
                columns: [{
                        data: 'nama',
                        name: 'nama',
                        className: 'align-middle'
                    },
                    {
                        data: 'no_inet',
                        name: 'no_inet',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'saldo',
                        name: 'saldo',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
                            return formatRupiah(data, 'Rp. ');
                        }
                    },
                    {
                        data: 'sto',
                        name: 'sto',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'nper',
                        name: 'nper',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'status_pembayaran',
                        name: 'status_pembayaran',
                        className: 'align-middle text-center',
                        render: function(data, type, row) {
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
                        data: 'opsi-tabel-dataalladminbillper',
                        name: 'opsi-tabel-dataalladminbillper',
                        className: 'align-middle',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [4, 'desc']
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
    </script>
@endpush
