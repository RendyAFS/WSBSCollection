<nav class="navbar navbar-expand-lg px-4 shadow shadow-sm border">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <img src="{{ asset('storage/file_assets/logo-telkom2.png') }}" alt="" id="nav-logo-telkom">
        <span class="fw-bold fs-2 d-none d-md-block">
            {{$title}}
        </span>
        <div class="dropdown ms-auto" id="drowpdown-account">
            <button class="btn btn-soft-grey d-flex align-items-center account fw-bold"  type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill me-2"></i>
                <span class="d-none d-md-block me-2"> {{ Auth::user()->name }} </span>
                <i class="bi bi-caret-down-fill"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
