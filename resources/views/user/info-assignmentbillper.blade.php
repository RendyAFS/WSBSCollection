@extends('layouts.app-user')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Info Assignment
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="row">
                <form action="{{ route('update-assignmentbillper', ['id' => $all->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">{{ $all->nama }}</h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $all->no_inet }}</h6>
                                    </div>
                                    <hr class="border border-dark border-3 opacity-75 my-4">
                                    <div class="contain-form">

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
                                        <div class="mb-3 d-none">
                                            <label for="all_id" class="form-label">id</label>
                                            <input type="text" class="form-control" id="all_id" name="all_id"
                                                value="{{ $all->id }}">
                                        </div>


                                        <div class="mb-3">
                                            <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                                            <input type="text" class="form-control  bg-body-secondary" id="status_pembayaran " name="status_pembayaran"
                                                value="{{ $all->status_pembayaran }}" readonly>
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
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="saldo" name="saldo" value="{{ $all->saldo }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="sto" class="form-label fw-bold">STO</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="sto" name="sto" value="{{ $all->sto }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="umur_customer" class="form-label fw-bold">Umur Customer</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="umur_customer" name="umur_customer"
                                                value="{{ $all->umur_customer }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="produk" class="form-label fw-bold">Produk</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="produk" name="produk" value="{{ $all->produk }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nper" class="form-label fw-bold">NPER</label>
                                            <input type="text"
                                                class="form-control bg-secondary text-dark bg-opacity-25" readonly
                                                id="nper" name="nper" value="{{ $all->nper }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">Evidence {{ $all->user->name }} </h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $all->user->nik }}</h6>
                                    </div>
                                    <hr class="border border-dark border-3 opacity-75 my-4">
                                    <div class="contain-form">
                                        <div class="mb-3 d-none">
                                            <label for="user_name" class="form-label fw-bold">User Name</label>
                                            <input type="text" class="form-control" id="user_name"
                                                value="{{ Auth::user()->name }}" readonly>
                                            <input type="hidden" id="users_id" name="users_id"
                                                value="{{ Auth::user()->id }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="snd" class="form-label">SND</label>
                                            <input type="text" class="form-control" id="snd" name="snd"
                                                value="{{ $all->no_inet }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="snd" class="form-label">SND</label>
                                            <input type="text" class="form-control" id="snd" name="snd"
                                                value="{{ $all->no_inet }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="witel" class="form-label fw-bold">Witel</label>
                                            <input type="text" class="form-control" id="witel" name="witel"
                                                value="SBS">
                                        </div>
                                        <div class="mb-3">
                                            <label for="waktu_visit" class="form-label fw-bold">Waktu Visit</label>
                                            <input type="datetime-local" class="form-control" id="waktu_visit"
                                                name="waktu_visit"
                                                value="{{ old('waktu_visit', isset($sales_report->waktu_visit) ? \Carbon\Carbon::parse($sales_report->waktu_visit)->format('Y-m-d\TH:i') : '') }}"
                                                required>
                                        </div>



                                        <div class="mb-3">
                                            <label for="voc_kendalas_id" class="form-label fw-bold">Voc Kendala</label>

                                            <select class="form-select" id="voc_kendalas_id" name="voc_kendalas_id"
                                                required>
                                                <option value="" disabled selected>Pilih Kendala</option>
                                                @foreach ($voc_kendala as $kendala)
                                                    <option value="{{ $kendala->id }}"
                                                        {{ old('voc_kendalas_id') == $kendala->id ? 'selected' : '' }}>
                                                        {{ $kendala->voc_kendala }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="follow_up" class="form-label fw-bold">Follow Up</label>
                                            <input type="text" class="form-control" id="follow_up" name="follow_up"
                                                value="{{ old('follow_up') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="evidence_sales" class="form-label fw-bold">Evidence Sales</label>
                                            <input type="file" class="form-control" id="evidence_sales"
                                                name="evidence_sales" accept="image/*" required>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input border border-dark" type="checkbox"
                                                    id="toggleEvidencePembayaran" name="toggleEvidencePembayaran">
                                                <label class="form-check-label" for="toggleEvidencePembayaran">
                                                    Tambah Evidence Pembayaran
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3" id="evidencePembayaranWrapper" style="display: none;">
                                            <label for="evidence_pembayaran" class="form-label fw-bold">Evidence
                                                Pembayaran</label>
                                            <input type="file" class="form-control" id="evidence_pembayaran"
                                                name="evidence_pembayaran" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('reportassignmentbillper.index') }}" class="btn btn-grey w-25 me-2">Batal</a>
                        <button type="submit" class="btn btn-secondary w-25">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.getElementById('toggleEvidencePembayaran').addEventListener('change', function() {
            var wrapper = document.getElementById('evidencePembayaranWrapper');
            if (this.checked) {
                wrapper.style.display = 'block';
                document.getElementById('evidence_pembayaran').required = true;
            } else {
                wrapper.style.display = 'none';
                document.getElementById('evidence_pembayaran').required = false;
            }
        });
    </script>
@endpush
