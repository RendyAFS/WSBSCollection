<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <img src="{{ Vite::asset('resources/images/logo-telkom2.png') }}" alt="" id="side-logo-telkom">
        <span class="fs-4 fw-bold">Data Collection</span>
    </div>
    <ul class="list-unstyled components p-4">
        <li class="{{ Route::is('super-admin.index') ? 'active' : '' }}">
            <a href="{{ route('super-admin.index') }}">
                <i class="bi bi-grid{{ Route::is('super-admin.index') ? '-fill' : '' }}"></i>
                Dashboard
            </a>
        </li>

        <span class="fw-bold">Operasional</span>

        <li class="{{ Route::is('tools.index') ? 'active' : '' }}">
            <a href="{{ route('tools.index') }}">
                <i class="bi bi-wrench-adjustable-circle{{ Route::is('tools.index') ? '-fill' : '' }}"></i>
                Tool
            </a>
        </li>

        <li class="{{ Route::is('billper.index') ? 'active' : '' }}">
            <a href="{{ route('billper.index') }}">
                <i class="bi bi-database{{ Route::is('billper.index') ? '-fill' : '' }}"></i>
                Billper
            </a>
        </li>

        <span class="fw-bold">Profil</span>

        <li class="{{ Route::is('data-akun.index') ? 'active' : '' }}">
            <a href="{{ route('data-akun.index') }}">
                <i class="bi bi-people{{ Route::is('data-akun.index') ? '-fill' : '' }}"></i>
                Akun
            </a>
        </li>

    </ul>
</div>
