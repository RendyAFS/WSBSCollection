@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Data Billper
            </span>

            <div class="d-flex">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-submit me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-arrow-repeat"></i> Cek Pembayaran
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ route('download.excel') }}" class="btn btn-submit"> <i
                            class="bi bi-file-earmark-spreadsheet-fill"></i> Download</a>
                    <button type="button" class="btn btn-submit dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu p-3">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="fs-6" id="exampleModalLabel">Filter Download</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('download.filtered.excel') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="bulan_tahun">Pilih Bulan-Tahun</label>
                                            <input type="month" id="bulan_tahun" name="bulan_tahun" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-submit">
                                            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Download
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>


        <table class="table table-hover table-bordered datatable shadow" id="tabelbillpers" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="align-middle">Nama</th>
                    <th id="th" class="align-middle text-center">No. Inet</th>
                    <th id="th" class="align-middle text-center">Saldo</th>
                    <th id="th" class="align-middle text-center">No. Tlf</th>
                    <th id="th" class="align-middle">Email</th>
                    <th id="th" class="align-middle text-center">STO</th>
                    <th id="th" class="align-middle text-center">Umur Customer</th>
                    <th id="th" class="align-middle text-center">Produk</th>
                    <th id="th" class="align-middle text-center">Status Pembayaran</th>
                    <th id="th" class="align-middle text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($billpers as $data)
                    <tr data-id="{{ $data->id }}">
                        <td class="align-middle">
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
                        <td class="align-middle">
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
                            @if ($data->status_pembayaran == 'Unpaid')
                                <span class="fw-normal badge text-bg-warning">{{ $data->status_pembayaran }}</span>
                            @elseif($data->status_pembayaran == 'Paid')
                                <span class="fw-normal badge text-bg-success">{{ $data->status_pembayaran }}</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <form action="{{ route('destroy-billpers', ['id' => $data->id]) }}" method="POST"
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
@endsection
@push('scripts')
    <script type="module">
        // Table
        $(document).ready(function() {
            new DataTable('#tabelbillpers', {
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
    </script>
@endpush
