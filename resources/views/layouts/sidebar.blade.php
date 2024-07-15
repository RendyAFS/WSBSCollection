<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <img src="{{ Vite::asset('resources/images/logo-telkom2.png') }}" alt="" id="side-logo-telkom">
        <span class="fs-4 fw-bold">Data Collection</span>
    </div>
    <ul class="list-unstyled components p-4">
        @if (auth()->user()->level == 'Super Admin')
            <li class="{{ Route::is('super-admin.index') ? 'active' : '' }}">
                <a href="{{ route('super-admin.index') }}">
                    <i class="bi bi-grid{{ Route::is('super-admin.index') ? '-fill' : '' }}"></i>
                    Dashboard
                </a>
            </li>

            <span class="fw-bold">Manajer Data</span>

            <li class="{{ Route::is('datamaster.index') ? 'active' : '' }}">
                <a href="{{ route('datamaster.index') }}">
                    <i class="bi bi-database{{ Route::is('datamaster.index') ? '-fill' : '' }}"></i>
                    Data Master
                </a>
            </li>

            <li class="{{ Route::is('tools.index') ? 'active' : '' }}">
                <a href="{{ route('tools.index') }}">
                    <i class="bi bi-wrench-adjustable-circle{{ Route::is('tools.index') ? '-fill' : '' }}"></i>
                    Tool
                </a>
            </li>

            <li class="{{ Route::is('all.index') ? 'active' : '' }}">
                <a href="{{ route('all.index') }}">
                    <i class="bi bi-clipboard2-data{{ Route::is('all.index') ? '-fill' : '' }}"></i>
                    Data All
                </a>
            </li>
            <li class="{{ Route::is('reportdata.index') ? 'active' : '' }}">
                <a href="{{ route('reportdata.index') }}">
                    <i class="bi bi-flag{{ Route::is('reportdata.index') ? '-fill' : '' }}"></i>
                    Report
                </a>
            </li>

            <span class="fw-bold">Profil</span>

            <li class="{{ Route::is('data-akun.index') ? 'active' : '' }}">
                <a href="{{ route('data-akun.index') }}">
                    <i class="bi bi-people{{ Route::is('data-akun.index') ? '-fill' : '' }}"></i>
                    Akun
                </a>
            </li>
        @elseif(auth()->user()->level == 'Admin')
            <li class="{{ Route::is('admin.index') ? 'active' : '' }}">
                <a href="{{ route('admin.index') }}">
                    <i class="bi bi-grid{{ Route::is('admin.index') ? '-fill' : '' }}"></i>
                    Dashboard Admin
                </a>
            </li>

            <span class="fw-bold">Manajer Data</span>

            <li class="{{ Route::is('all-admin.index') ? 'active' : '' }}">
                <a href="{{ route('all-admin.index') }}">
                    <i class="bi bi-clipboard2-data{{ Route::is('all-admin.index') ? '-fill' : '' }}"></i>
                    Data All
                </a>
            </li>

        @elseif(auth()->user()->level == 'User')
            <li class="{{ Route::is('user.index') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="bi bi-grid{{ Route::is('user.index') ? '-fill' : '' }}"></i>
                    Dashboard User
                </a>
            </li>
        @endif
    </ul>
</div>
