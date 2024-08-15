<nav class="navbar navbar-expand-lg px-4 shadow shadow-sm border">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <img src="{{ asset('storage/file_assets/logo-telkom2.png') }}" alt="" id="nav-logo-telkom">
        <span class="fw-bold fs-2 d-none d-md-block">
            {{ $title }}
        </span>
        <div class="dropdown ms-auto" id="dropdown-account">
            <button class="btn btn-soft-grey d-flex align-items-center account fw-bold" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Cek Keberadaan Foto Profil -->
                @if (Auth::user()->foto_profile && Storage::exists('public/file_fotoprofile/' . Auth::user()->foto_profile))
                    <!-- Tampilkan Foto Profil -->
                    <img src="{{ asset('storage/file_fotoprofile/' . Auth::user()->foto_profile) }}" alt="Foto Profil"
                        class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                @else
                    <!-- Tampilkan Ikon Default -->
                    <i class="bi bi-person-fill me-2"></i>
                @endif

                <span class="d-none d-md-block me-2"> {{ Auth::user()->name }} </span>
                <i class="bi bi-caret-down-fill"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-start">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.index') }}">
                        <i class="bi bi-person-fill me-2"></i> Profilku
                    </a>

                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left me-2"></i> Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>
