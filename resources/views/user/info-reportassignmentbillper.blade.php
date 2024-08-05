@extends('layouts.app-user')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3 d-block d-md-none">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Info Report Assignment
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="row">
                <form action="{{ route('update-reportassignmentbillper', ['id' => $sales_report->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card px-3 py-4 shadow">
                        <div class="card-body">
                            <div class="contain-header mb-3">
                                <h5 class="card-title">{{$sales_report->billpers->nama }}</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">{{ $sales_report->snd }}</h6>
                            </div>
                            <hr class="border border-dark border-3 opacity-75 my-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
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
                                                value="{{ $sales_report->snd }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status_pembayaran" class="form-label fw-bold">Status Pembayaran</label>
                                            <input type="text" class="form-control bg-body-secondary" id="status_pembayaran" name="status_pembayaran"
                                                value="{{$sales_report->billpers->status_pembayaran}}" readonly>
                                        </div>
                                        <div class="mb-3 d-none">
                                            <label for="witel" class="form-label fw-bold">Witel</label>
                                            <input type="text" class="form-control" id="witel" name="witel"
                                                value="{{ $sales_report->witel }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="waktu_visit" class="form-label fw-bold">Waktu Visit</label>
                                            <input type="datetime-local" class="form-control" id="waktu_visit" name="waktu_visit"
                                                value="{{ $sales_report->waktu_visit ? \Carbon\Carbon::parse($sales_report->waktu_visit)->format('Y-m-d\TH:i:s') : '' }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jmlh_visit" class="form-label fw-bold">Visit</label>
                                            <input type="text" class="form-control bg-body-secondary" id="jmlh_visit" name="jmlh_visit"
                                                value="{{$sales_report->jmlh_visit}}" readonly>
                                        </div>



                                        <div class="mb-3">
                                            <label for="voc_kendalas_id" class="form-label fw-bold">Voc Kendala</label>
                                            <select class="form-select" id="voc_kendalas_id" name="voc_kendalas_id"
                                                required>
                                                <option value="" disabled selected>Pilih Kendala</option>
                                                @foreach ($voc_kendala as $kendala)
                                                    <option value="{{ $kendala->id }}"
                                                        {{ $sales_report->voc_kendalas_id == $kendala->id ? 'selected' : '' }}>
                                                        {{ $kendala->voc_kendala }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="follow_up" class="form-label fw-bold">Follow Up</label>
                                            <input type="text" class="form-control" id="follow_up" name="follow_up"
                                                value="{{ $sales_report->follow_up }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="evidence_sales" class="form-label fw-bold">Evidence Sales</label>
                                        <input type="file" class="form-control" id="evidence_sales" name="evidence_sales"
                                            accept="image/*" onchange="previewImage(event, 'preview_sales')">
                                        <div class="mt-2">
                                            <a href="{{ $sales_report->evidence_sales ? asset('storage/file_evidence/' . $sales_report->evidence_sales) : '#' }}"
                                                target="_blank">
                                                <img id="preview_sales"
                                                    src="{{ $sales_report->evidence_sales ? asset('storage/file_evidence/' . $sales_report->evidence_sales) : '' }}"
                                                    alt="Preview Sales" class="img-preview">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="evidence_pembayaran" class="form-label fw-bold">Evidence
                                            Pembayaran</label>
                                        <input type="file" class="form-control" id="evidence_pembayaran"
                                            name="evidence_pembayaran" accept="image/*"
                                            onchange="previewImage(event, 'preview_pembayaran')">
                                        <div class="mt-2">
                                            <a href="{{ $sales_report->evidence_pembayaran ? asset('storage/file_evidence/' . $sales_report->evidence_pembayaran) : '#' }}"
                                                target="_blank">
                                                <img id="preview_pembayaran"
                                                    src="{{ $sales_report->evidence_pembayaran ? asset('storage/file_evidence/' . $sales_report->evidence_pembayaran) : '' }}"
                                                    alt="Preview Pembayaran" class="img-preview">
                                            </a>
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
    <script type="module"></script>
@endpush
