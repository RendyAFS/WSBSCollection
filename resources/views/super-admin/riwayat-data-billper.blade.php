@extends('layouts.app-super-admin')

@extends('layouts.loading')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">
                Riwayat Data Billper
            </span>
        </div>

        <table class="table table-hover table-bordered datatable shadow" id="tabelbillpersriwayat" style="width: 100%">
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
                    <th id="th" class="align-middle text-center">created_at</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="module">
        // Table initialization
        $(document).ready(function() {
            var dataTable = new DataTable('#tabelbillpersriwayat', {
                serverSide: true,
                processing: true,
                pagingType: "simple_numbers",
                responsive: true,
                ajax: {
                    url: "{{ route('gettabelbillpersriwayat') }}",
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
                        data: 'no_tlf',
                        name: 'no_tlf',
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
                        data: 'nper',
                        name: 'nper',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'umur_customer',
                        name: 'umur_customer',
                        className: 'align-middle text-center'
                    },
                    {
                        data: 'produk',
                        name: 'produk',
                        className: 'align-middle text-center',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        className: 'align-middle text-center',
                        visible: false
                    },

                ],
                order: [
                    [9, 'desc']
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
