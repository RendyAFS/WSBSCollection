@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2 mb-3 mb-md-0">
                Edit Preview Data Master
            </span>
        </div>

        <div class="px-0 px-md-5">
            <div class="card px-3 py-4 shadow">
                <div class="card-body">
                    <div class="contain-header mb-3">
                        <h5 class="card-title">{{ $preview_data_master->pelanggan }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $preview_data_master->event_source }}</h6>
                    </div>
                    <hr class="border border-dark border-3 opacity-75 my-4">
                    <div class="contain-form">
                        <form action="{{ route('update-previewdatamasters', ['id' => $preview_data_master->id]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3 d-none">
                                        <label for="event_source" class="form-label fw-bold">Event Source</label>
                                        <input type="text" class="form-control" id="event_source" name="event_source"
                                            value="{{ $preview_data_master->event_source }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pelanggan" class="form-label fw-bold">Nama Pelanggan</label>
                                        <input type="text" class="form-control" id="pelanggan" name="pelanggan"
                                            value="{{ $preview_data_master->pelanggan }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="csto" class="form-label fw-bold">CSTO</label>
                                        <input type="text" class="form-control" id="csto" name="csto"
                                            value="{{ $preview_data_master->csto }}">
                                    </div>

                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="mobile_contact_tel" class="form-label fw-bold">No. Tlf</label>
                                        <input type="text" class="form-control" id="mobile_contact_tel"
                                            name="mobile_contact_tel" value="{{ $preview_data_master->mobile_contact_tel }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email_address" class="form-label fw-bold">Email</label>
                                        <input type="text" class="form-control" id="email_address" name="email_address"
                                            value="{{ $preview_data_master->email_address }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat_pelanggan" class="form-label fw-bold">Alamat Pelanggan</label>
                                        <input type="text" class="form-control" id="alamat_pelanggan"
                                            name="alamat_pelanggan" value="{{ $preview_data_master->alamat_pelanggan }}">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('datamaster.index') }}" class="btn btn-grey w-25 me-2">Batal</a>
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
    <script type="module"></script>
@endpush
