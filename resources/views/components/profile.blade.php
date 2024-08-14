@extends('layouts.app-akun')

@section('content')
    <div class="px-3 py-4">
        <div class="mb-4 d-block d-md-none">
            <span class="fw-bold fs-2">
                My Profile
            </span>
        </div>

        <div class="row">
            <div class="col-12 col-xl-3 mb-3 mb-xl-0">
                <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
                    <!-- Foto Profil atau Ikon Default -->
                    <div class="text-center mb-3">
                        @if ($user->foto_profile && Storage::exists('public/file_fotoprofile/' . $user->foto_profile))
                            <!-- Tampilkan Foto Profil -->
                            <img src="{{ Storage::url('public/file_fotoprofile/' . $user->foto_profile) }}" alt="Foto Profil"
                                class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <!-- Tampilkan Ikon Default -->
                            <i class="bi bi-person-circle fs-1 text-center text-secondary"></i>
                        @endif
                    </div>
                    <span class="fs-5 fw-bold text-center d-block">
                        {{ $user->name }}
                    </span>
                    <div class="text-center">
                        <span
                            class="badge bg-{{ $user->level == 'Super Admin'
                                ? 'danger'
                                : ($user->level == 'Admin Billper'
                                    ? 'success'
                                    : ($user->level == 'Admin Pranpc'
                                        ? 'primary'
                                        : ($user->level == 'Sales'
                                            ? 'secondary'
                                            : 'default'))) }}">
                            {{ $user->level }}
                        </span>
                    </div>
                    <span class="text-secondary text-center d-block">
                        {{ $user->nik }}
                    </span>
                    <span class="text-secondary text-center d-block">
                        {{ $user->email }}
                    </span>
                </div>

            </div>
            <div class="col-12 col-xl-9">
                <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4">
                    <span class="fs-4 fw-bold mb-3">
                        Edit Akun
                    </span>
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Level dan Status (d-none berarti tersembunyi) -->
                        <div class="mb-3 d-none">
                            <label for="level" class="form-label fw-bold">Level</label>
                            <input type="text" name="level" class="form-control @error('level') is-invalid @enderror"
                                id="level" value="{{ old('level', $user->level) }}" required>
                            @error('level')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 d-none">
                            <label for="status" class="form-label fw-bold">Status</label>
                            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                                id="status" value="{{ old('status', $user->status) }}" required>
                            @error('status')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="mb-3">
                            <label for="nik" class="form-label fw-bold">NIK</label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                                id="nik" value="{{ old('nik', $user->nik) }}" required>
                            @error('nik')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- No HP -->
                        <div class="mb-3">
                            <label for="no_hp" class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
                            @error('no_hp')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Preview Crop Foto -->
                        <div class="mb-3" id="preview-container" style="display: none;">
                            <label for="crop_foto_profile" class="form-label fw-bold">Preview Foto Profil</label>
                            <!-- Image preview container -->
                            <div id="crop-preview-container">
                                <img id="crop-preview" src="#" alt="Cropped Preview"
                                    style="max-width: 100%; max-height: 250px;">
                            </div>
                        </div>



                        <!-- Form Upload Foto -->
                        <div class="mb-3">
                            <label for="foto_profile" class="form-label fw-bold">Foto Profil</label>
                            <input type="file" name="foto_profile"
                                class="form-control @error('foto_profile') is-invalid @enderror" id="foto_profile"
                                accept="image/*">
                            @error('foto_profile')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="ubah-password">

                            <!-- Checkbox untuk mengubah password -->
                            <div class="mb-3">
                                <input type="checkbox" class="form-check-input shadow shadow-sm" id="changePassword"
                                    name="change_password" {{ old('change_password') ? 'checked' : '' }}>
                                <label for="changePassword" class="form-label fw-bold text-secondary">Ubah
                                    Password</label>
                            </div>

                            <!-- Input password lama, hanya ditampilkan jika checkbox dicentang -->
                            <div class="mb-3" id="oldPasswordContainer"
                                style="display: {{ old('change_password') ? 'block' : 'none' }};">
                                <label for="old_password" class="form-label fw-bold">Password Lama</label>
                                <input type="password" name="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror" id="old_password">
                                @error('old_password')
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Input password baru, hanya ditampilkan jika checkbox dicentang -->
                            <div class="mb-3" id="newPasswordContainer"
                                style="display: {{ old('change_password') ? 'block' : 'none' }};">
                                <label for="password" class="form-label fw-bold">Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password">
                                @error('password')
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Input konfirmasi password baru, hanya ditampilkan jika checkbox dicentang -->
                            <div class="mb-3" id="passwordConfirmationContainer"
                                style="display: {{ old('change_password') ? 'block' : 'none' }};">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password
                                    Baru</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation">
                                @error('password_confirmation')
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Checkbox untuk menampilkan password -->
                            <div class="mb-3" id="showPasswordsContainer"
                                style="display: {{ old('change_password') ? 'block' : 'none' }};">
                                <input type="checkbox" id="showPasswords" class="form-check-input shadow shadow-sm">
                                <label for="showPasswords" class="form-label fw-bold text-secondary">Tampilkan
                                    Password</label>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-secondary">Update Akun</button>
                        </div>
                    </form>

                    <!-- Container untuk Cropper -->
                    <div id="cropper-container" style="display: none;">
                        <div class="px-4 py-4 card shadow shadow-sm border border-0 rounded-4 container-background">
                            <div class="d-flex justify-content-center align-items-center" style="height: 500px;">
                                <img id="cropper-image" src="#" alt="Crop Image"
                                    style="max-width: 100%; max-height: 100%;">
                            </div>
                            <button type="button" class="btn btn-primary mt-3" id="cropImageBtn">Crop &
                                Save</button>
                            <button type="button" class="btn btn-secondary mt-3" id="cancelCropBtn">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Data gagal diperbarui. Periksa kembali input Anda.',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection

@push('scripts')
    <script type="module">
        // Fungsi untuk mengontrol visibilitas input password dan checkbox "Tampilkan Password"
        document.getElementById('changePassword').addEventListener('change', function() {
            const isChecked = this.checked;
            const elementsToShowHide = ['#oldPasswordContainer', '#newPasswordContainer',
                '#passwordConfirmationContainer'
            ];
            const showPasswordsContainer = document.getElementById('showPasswordsContainer');

            elementsToShowHide.forEach(selector => {
                document.querySelector(selector).style.display = isChecked ? 'block' : 'none';
            });

            // Mengontrol visibilitas checkbox "Tampilkan Password"
            showPasswordsContainer.style.display = isChecked ? 'block' : 'none';
        });

        // Fungsi untuk menampilkan atau menyembunyikan password
        document.getElementById('showPasswords').addEventListener('change', function() {
            const passwordFields = ['old_password', 'password', 'password_confirmation'];
            passwordFields.forEach(id => {
                const input = document.getElementById(id);
                input.type = this.checked ? 'text' : 'password';
            });
        });



        // Foto Edit
        document.addEventListener('DOMContentLoaded', function() {
            const inputFile = document.getElementById('foto_profile');
            const cropperContainer = document.getElementById('cropper-container');
            const cropperImage = document.getElementById('cropper-image');
            const cancelCropBtn = document.getElementById('cancelCropBtn');
            const cropperImageBtn = document.getElementById('cropImageBtn');
            const previewContainer = document.getElementById('preview-container');
            const cropPreview = document.getElementById('crop-preview');
            let cropper;
            let originalFileName = ''; // Store the original file name

            inputFile.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    originalFileName = file.name; // Store the original file name
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        cropperImage.src = e.target.result;
                        cropperContainer.style.display = 'flex'; // Show cropper container

                        // Initialize Cropper.js
                        if (cropper) {
                            cropper.destroy();
                        }

                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 1,
                            viewMode: 1,
                            autoCropArea: 1,
                            zoomable: false, // Disable zoom if not needed
                        });
                    };

                    reader.readAsDataURL(file);
                }
            });

            cropperImageBtn.addEventListener('click', function() {
                if (cropper) {
                    cropper.getCroppedCanvas({
                        width: 150,
                        height: 150
                    }).toBlob(function(blob) {
                        // Create a new file name with the original file name
                        const newFileName = `cropped-photo-${originalFileName}`;
                        const file = new File([blob], newFileName, {
                            type: 'image/jpeg'
                        });

                        // Create a new DataTransfer to simulate a file input change
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        // Update the input file with the new cropped image
                        inputFile.files = dataTransfer.files;

                        // Show the preview of the cropped image
                        const croppedImageURL = URL.createObjectURL(blob);
                        cropPreview.src = croppedImageURL;
                        previewContainer.style.display = 'block'; // Show preview container

                        // Hide the cropper container
                        cropperContainer.style.display = 'none';

                        // Optionally, clear the cropper instance
                        cropper.destroy();
                        cropper = null;
                    }, 'image/jpeg');
                }
            });

            cancelCropBtn.addEventListener('click', function() {
                // Hide the cropper container and reset input file
                cropperContainer.style.display = 'none';
                if (cropper) {
                    cropper.destroy();
                }
                inputFile.value = ''; // Reset the file input
                previewContainer.style.display = 'none'; // Hide preview container
            });
        });
    </script>
@endpush
