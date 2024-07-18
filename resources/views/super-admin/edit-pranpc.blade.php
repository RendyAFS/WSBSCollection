@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Edit Data PraNPC
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="card px-3 py-4 shadow">
                <div class="card-body">
                    <div class="contain-header mb-3">
                        <h5 class="card-title">{{ $pranpc->nama }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $pranpc->snd }}</h6>
                    </div>
                    <hr class="border border-dark border-3 opacity-75 my-4">
                    <div class="contain-form">
                        <form action="{{ route('update-pranpcs', ['id' => $pranpc->id]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                                        <select class="form-select" id="status_pembayaran" name="status_pembayaran">
                                            <option value="Paid"
                                                {{ $pranpc->status_pembayaran == 'Paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="Pending"
                                                {{ $pranpc->status_pembayaran == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Unpaid"
                                                {{ $pranpc->status_pembayaran == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 d-none">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ $pranpc->nama }}">
                                    </div>
                                    <div class="mb-3 d-none">
                                        <label for="snd" class="form-label">SND</label>
                                        <input type="text" class="form-control" id="snd" name="snd"
                                            value="{{ $pranpc->snd }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="multi_kontak1" class="form-label">Nomor Telfon</label>
                                        <input type="text" class="form-control" id="multi_kontak1" name="multi_kontak1"
                                            value="{{ $pranpc->multi_kontak1 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            value="{{ $pranpc->email }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            value="{{ $pranpc->alamat }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="bill_bln" class="form-label">Bill Bulan</label>
                                        <input type="text" class="form-control bg-body-secondary" id="bill_bln"
                                            name="bill_bln" value="{{ $pranpc->bill_bln }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bill_bln1" class="form-label">Bill Bulan 1</label>
                                        <input type="text" class="form-control bg-body-secondary" id="bill_bln1"
                                            name="bill_bln1" value="{{ $pranpc->bill_bln1 }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mintgk" class="form-label">Mintgk</label>
                                        <input type="text" class="form-control bg-body-secondary" id="mintgk"
                                            name="mintgk" value="{{ $pranpc->mintgk }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="maxtgk" class="form-label">Maxtgk</label>
                                        <input type="text" class="form-control bg-body-secondary" id="maxtgk"
                                            name="maxtgk" value="{{ $pranpc->maxtgk }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('pranpc.index') }}" class="btn btn-grey w-25 me-2">Batal</a>
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
