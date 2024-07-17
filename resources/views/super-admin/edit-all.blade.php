@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Edit Data All
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="card px-3 py-4 shadow">
                <div class="card-body">
                    <div class="contain-header mb-3">
                        <h5 class="card-title">{{ $all->nama }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $all->no_inet }}</h6>
                    </div>
                    <hr class="border border-dark border-3 opacity-75 my-4">
                    <div class="contain-form">
                        <form action="{{ route('update-alls', ['id' => $all->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3 d-none">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $all->nama }}">
                            </div>
                            <div class="mb-3 d-none">
                                <label for="no_inet" class="form-label">No. Inet</label>
                                <input type="text" class="form-control" id="no_inet" name="no_inet"
                                    value="{{ $all->no_inet }}">
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                                        <select class="form-select" id="status_pembayaran" name="status_pembayaran">
                                            <option value="Paid" {{ $all->status_pembayaran == 'Paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="Unpaid" {{ $all->status_pembayaran == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_tlf" class="form-label fw-bold">Nomor Telfon</label>
                                        <input type="text" class="form-control" id="no_tlf" name="no_tlf"
                                            value="{{ $all->no_tlf }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            value="{{ $all->email }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="saldo" class="form-label fw-bold">Saldo</label>
                                        <input type="text" class="form-control bg-secondary text-dark bg-opacity-25" readonly id="saldo" name="saldo"
                                            value="{{ $all->saldo }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="sto" class="form-label fw-bold">STO</label>
                                        <input type="text" class="form-control bg-secondary text-dark bg-opacity-25" readonly id="sto" name="sto"
                                            value="{{ $all->sto }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="umur_customer" class="form-label fw-bold">Umur Customer</label>
                                        <input type="text" class="form-control bg-secondary text-dark bg-opacity-25" readonly id="umur_customer" name="umur_customer"
                                            value="{{ $all->umur_customer }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="produk" class="form-label fw-bold">Produk</label>
                                        <input type="text" class="form-control bg-secondary text-dark bg-opacity-25" readonly id="produk" name="produk"
                                            value="{{ $all->produk }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nper" class="form-label fw-bold">NPER</label>
                                        <input type="text" class="form-control bg-secondary text-dark bg-opacity-25" readonly id="nper" name="nper"
                                            value="{{ $all->nper }}">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('all.index')}}" class="btn btn-grey w-25 me-2">Batal</a>
                                <button type="submit" class="btn btn-secondary w-25">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@push('scripts')
    <script type="module">
        //
    </script>
@endpush
