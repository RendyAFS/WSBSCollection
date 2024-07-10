@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Edit Data Billper
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="card px-3 py-4 shadow">
                <div class="card-body">
                    <div class="contain-header mb-3">
                        <h5 class="card-title">{{ $billper->nama }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $billper->no_inet }}</h6>
                    </div>
                    <hr class="border border-dark border-3 opacity-75 my-4">
                    <div class="contain-form">
                        <form action="{{ route('update-billpers', ['id' => $billper->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3 d-none">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $billper->nama }}">
                            </div>
                            <div class="mb-3 d-none">
                                <label for="no_inet" class="form-label">No. Inet</label>
                                <input type="text" class="form-control" id="no_inet" name="no_inet"
                                    value="{{ $billper->no_inet }}">
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="saldo" class="form-label fw-bold">Saldo</label>
                                        <input type="text" class="form-control" id="saldo" name="saldo"
                                            value="{{ $billper->saldo }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                                        <select class="form-select" id="status_pembayaran" name="status_pembayaran">
                                            <option value="Paid" {{ $billper->status_pembayaran == 'Paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="Unpaid" {{ $billper->status_pembayaran == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_tlf" class="form-label fw-bold">No. Tlf</label>
                                        <input type="text" class="form-control" id="no_tlf" name="no_tlf"
                                            value="{{ $billper->no_tlf }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $billper->email }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="sto" class="form-label fw-bold">STO</label>
                                        <input type="text" class="form-control" id="sto" name="sto"
                                            value="{{ $billper->sto }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="umur_customer" class="form-label fw-bold">Umur Customer</label>
                                        <input type="text" class="form-control" id="umur_customer" name="umur_customer"
                                            value="{{ $billper->umur_customer }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="produk" class="form-label fw-bold">Produk</label>
                                        <input type="text" class="form-control" id="produk" name="produk"
                                            value="{{ $billper->produk }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bulan_tahun" class="form-label fw-bold">Bulan-Tahun</label>
                                        <input type="text" class="form-control" id="bulan_tahun" name="bulan_tahun"
                                            value="{{ $billper->bulan_tahun }}">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{route('billper.index')}}" class="btn btn-grey w-25 me-2">Batal</a>
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
