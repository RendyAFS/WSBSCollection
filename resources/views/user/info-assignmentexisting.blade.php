@extends('layouts.app-user')

@section('content')
    <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3 d-block d-md-none">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Info Assignment
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="row">
                <form action="{{ route('update-assignmentexisting', ['id' => $existing->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">{{ $existing->nama }}</h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $existing->no_inet }}</h6>
                                    </div>
                                    <hr class="border border-dark border-3 opacity-75 my-4">
                                    <div class="contain-form">

                                        <div class="mb-3 d-none">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                value="{{ $existing->nama }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="no_inet" class="form-label">No. Inet</label>
                                            <input type="text" class="form-control" id="no_inet" name="no_inet"
                                                value="{{ $existing->no_inet }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="existing_id" class="form-label">id</label>
                                            <input type="text" class="form-control" id="existing_id" name="existing_id"
                                                value="{{ $existing->id }}">
                                        </div>


                                        <div class="mb-3">
                                            <label for="status_pembayaran" class="form-label fw-bold">Status
                                                Pembayaran</label>
                                            <input type="text" class="form-control  bg-body-secondary"
                                                id="status_pembayaran " name="status_pembayaran"
                                                value="{{ $existing->status_pembayaran }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="no_tlf" class="form-label fw-bold">Nomor Telfon</label>
                                            <input type="text" class="form-control" id="no_tlf" name="no_tlf"
                                                value="{{ $existing->no_tlf }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-bold">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $existing->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="saldo" class="form-label fw-bold">Saldo</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="saldo" name="saldo" value="{{ $existing->saldo }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="sto" class="form-label fw-bold">STO</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="sto" name="sto" value="{{ $existing->sto }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="umur_customer" class="form-label fw-bold">Umur Customer</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="umur_customer" name="umur_customer"
                                                value="{{ $existing->umur_customer }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="produk" class="form-label fw-bold">Produk</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="produk" name="produk" value="{{ $existing->produk }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nper" class="form-label fw-bold">NPER</label>
                                            <input type="text"
                                                class="form-control bg-secondary text-dark bg-opacity-25" readonly
                                                id="nper" name="nper" value="{{ $existing->nper }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">Evidence {{ $existing->user->name }} </h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $existing->user->nik }}</h6>
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
                                                value="{{ $existing->no_inet }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="witel" class="form-label fw-bold">Witel</label>
                                            <input type="text" class="form-control" id="witel" name="witel"
                                                value="SBS">
                                        </div>
                                        <div class="mb-3">
                                            <label for="waktu_visit" class="form-label fw-bold">Waktu Visit</label>
                                            <input type="datetime-local" class="form-control" id="waktu_visit"
                                                name="waktu_visit" value="" required>
                                        </div>
                                        <!-- Select Jmlh Visit -->
                                        <div class="mb-3">
                                            <label for="jmlh_visit" class="form-label fw-bold">Jumlah Visit</label>
                                            <select class="form-select" id="jmlh_visit" name="jmlh_visit" required>
                                                <option value="" disabled selected>Pilih Jumlah Visit</option>
                                                <option value="Visit 1" {{ $isSalesReportEmpty ? '' : 'disabled' }}>Visit
                                                    1</option>
                                                <option value="Visit 2"
                                                    {{ !$isSalesReportEmpty && $jmlh_visit == 'Visit 1' ? '' : 'disabled' }}>
                                                    Visit
                                                    2</option>
                                            </select>
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

                                        <!-- Wrapper for evidence_pembayaran input (Initially hidden with d-none) -->
                                        <div class="mb-3 d-none" id="evidencePembayaranWrapper">
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
                        <a href="{{ route('assignmentexisting.index') }}" class="btn btn-grey me-2 px-5">Batal</a>
                        <button type="submit" class="btn btn-secondary px-5">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            var vocSelect = document.getElementById('voc_kendalas_id');
            var evidenceWrapper = document.getElementById('evidencePembayaranWrapper');
            var evidenceInput = document.getElementById('evidence_pembayaran');

            // Function to show or hide evidence_pembayaran input based on select value
            function updateEvidenceInput() {
                if (vocSelect.value === '1') { // '1' represents 'Tidak Ada'
                    evidenceWrapper.classList.remove('d-none');
                    evidenceWrapper.classList.add('d-block');
                    evidenceInput.required = true;
                } else {
                    evidenceWrapper.classList.remove('d-block');
                    evidenceWrapper.classList.add('d-none');
                    evidenceInput.required = false;
                }
            }

            // Initial update based on the current select value
            updateEvidenceInput();

            // Handle change event for the voc_kendalas_id select
            vocSelect.addEventListener('change', updateEvidenceInput);
        });
    </script>
@endpush
