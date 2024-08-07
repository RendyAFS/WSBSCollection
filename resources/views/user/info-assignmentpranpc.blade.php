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
                <form action="{{ route('update-assignmentpranpc', ['id' => $pranpc->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">{{ $pranpc->nama }}</h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $pranpc->snd }}</h6>
                                    </div>
                                    <hr class="border border-dark border-3 opacity-75 my-4">
                                    <div class="contain-form">
                                        <div class="mb-3 d-none">
                                            <label for="user_name" class="form-label fw-bold">User Name</label>
                                            <input type="text" class="form-control" id="user_name"
                                                value="{{ Auth::user()->name }}" readonly>
                                            <input type="text" class="form-control" id="users_id" name="users_id"
                                                value="{{ Auth::user()->id }}">
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
                                        <div class="mb-3 d-none">
                                            <label for="pranpc_id" class="form-label">id</label>
                                            <input type="text" class="form-control" id="pranpc_id" name="pranpc_id"
                                                value="{{ $pranpc->id }}">
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="alamat" class="form-label">id</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat"
                                                value="{{ $pranpc->alamat }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status_pembayaran" class="form-label fw-bold">Status
                                                Pembayaran</label>
                                            <input type="text" class="form-control  bg-body-secondary"
                                                id="status_pembayaran " name="status_pembayaran"
                                                value="{{ $pranpc->status_pembayaran }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="multi_kontak1" class="form-label fw-bold">Nomor Telfon</label>
                                            <input type="text" class="form-control" id="multi_kontak1"
                                                name="multi_kontak1" value="{{ $pranpc->multi_kontak1 }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-bold">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $pranpc->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="bill_bln" class="form-label fw-bold">Bill Bln</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="bill_bln" name="bill_bln" value="{{ $pranpc->bill_bln }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="bill_bln1" class="form-label fw-bold">Bill Bln1</label>
                                            <input type="text" class="form-control bg-secondary text-dark bg-opacity-25"
                                                readonly id="bill_bln1" name="bill_bln1" value="{{ $pranpc->bill_bln1 }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="sto" class="form-label fw-bold">STO</label>
                                            <input type="text"
                                                class="form-control bg-secondary text-dark bg-opacity-25" readonly
                                                id="sto" name="sto" value="{{ $pranpc->sto }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="mintgk" class="form-label fw-bold">Mintgk</label>
                                            <input type="text"
                                                class="form-control bg-secondary text-dark bg-opacity-25" readonly
                                                id="mintgk" name="mintgk" value="{{ $pranpc->mintgk }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="maxtgk" class="form-label fw-bold">Maxtgk</label>
                                            <input type="text"
                                                class="form-control bg-secondary text-dark bg-opacity-25" readonly
                                                id="maxtgk" name="maxtgk" value="{{ $pranpc->maxtgk }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                            <div class="card px-3 py-4 shadow">
                                <div class="card-body">
                                    <div class="contain-header mb-3">
                                        <h5 class="card-title">Evidence {{ $pranpc->user->name }} </h5>
                                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $pranpc->user->nik }}</h6>
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
                                                value="{{ $pranpc->snd }}">
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
                        <a href="{{ route('assignmentpranpc.index') }}" class="btn btn-grey w-25 me-2">Batal</a>
                        <button type="submit" class="btn btn-secondary w-25">Update</button>
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
            var toggleCheckbox = document.getElementById('toggleEvidencePembayaran');
            var evidenceWrapper = document.getElementById('evidencePembayaranWrapper');
            var evidenceInput = document.getElementById('evidence_pembayaran');

            // Function to update checkbox state based on select value
            function updateCheckboxState() {
                if (vocSelect.value === '1') { // Jika nilai adalah '1', maka aktifkan checkbox
                    toggleCheckbox.disabled = false;
                } else {
                    toggleCheckbox.disabled = true;
                    toggleCheckbox.checked = false;
                    evidenceWrapper.style.display = 'none';
                    evidenceInput.required = false;
                }
            }

            // Initial update based on the current select value
            updateCheckboxState();

            // Handle change event for the voc_kendalas_id select
            vocSelect.addEventListener('change', updateCheckboxState);

            // Handle change event for the toggleEvidencePembayaran checkbox
            toggleCheckbox.addEventListener('change', function() {
                if (!this.disabled) {
                    if (this.checked) {
                        evidenceWrapper.style.display = 'block';
                        evidenceInput.required = true;
                    } else {
                        evidenceWrapper.style.display = 'none';
                        evidenceInput.required = false;
                    }
                }
            });
        });
    </script>
@endpush
