@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4">
            <span class="fw-bold fs-2">
                Tools
            </span>
        </div>

        <form action="{{ route('vlookup.perform') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file1" class="form-label">File dengen SND</label>
                <input class="form-control" type="file" name="file1" id="file1" required>
            </div>
            <div class="mb-3">
                <label for="file2" class="form-label">File dengan EVENT_SOURCE</label>
                <input class="form-control" type="file" name="file2" id="file2" required>
            </div>
            <button type="submit" class="btn btn-primary">Vlookup Data</button>
        </form>
    </div>
@endsection
