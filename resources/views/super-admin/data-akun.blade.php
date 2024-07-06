@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Data Akun
            </span>
        </div>
        <table class="table table-hover table-bordered datatable shadow" id="tabelakun" style="width: 100%">
            <thead class="fw-bold">
                <tr>
                    <th id="th" class="w-50">Nama</th>
                    <th id="th" class="text-center w-25">Jenis Akun</th>
                    <th id="th" class="text-center w-25">Status</th>
                    <th id="th" class="text-center">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr data-id="{{ $user->id }}">
                        <th>
                            <span class="fw-normal">{{ $user->name }}</span>
                        </th>
                        <th class="text-center">
                            <span class="fw-normal">{{ $user->level }}</span>
                        </th>
                        <th>
                            <select class="form-select form-select-sm user-status" aria-label="Small select example">
                                <option value="Aktif" class="text-success fw-bold"
                                    {{ $user->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Belum Aktif" class="text-danger fw-bold"
                                    {{ $user->status == 'Belum Aktif' ? 'selected' : '' }}>Belum Aktif</option>
                            </select>
                        </th>
                        <th class="text-center">
                            <form action="{{ route('destroy-akun', ['id' => $user->id]) }}" method="POST"
                                class="delete-form">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm me-2 btn-delete shadow">
                                    <i class="bi-trash"></i>
                                </button>
                            </form>
                            </form>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="module">
        $(document).ready(function() {
            new DataTable('#tabelakun', {
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
        document.addEventListener('DOMContentLoaded', function() {
            var selects = document.querySelectorAll('.user-status');
            selects.forEach(function(select) {
                setSelectColor(select);
                select.addEventListener('change', function() {
                    setSelectColor(select);
                });
            });

            function setSelectColor(select) {
                select.classList.add('fw-bold');
                if (select.value === 'Aktif') {
                    select.classList.add('text-success');
                    select.classList.remove('text-danger');
                } else if (select.value === 'Belum Aktif') {
                    select.classList.add('text-danger');
                    select.classList.remove('text-success');
                }
            }
        });
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
