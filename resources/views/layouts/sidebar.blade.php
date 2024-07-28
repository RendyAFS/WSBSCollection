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

            <li class="{{ Route::is('datamaster.index') ? 'active' : '' }}">
                <a href="{{ route('datamaster.index') }}">
                    <i class="bi bi-database{{ Route::is('datamaster.index') ? '-fill' : '' }}"></i>
                    Data Master
                </a>
            </li>

            <li class="{{ Route::is('reportdata.index') ? 'active' : '' }}">
                <a href="{{ route('reportdata.index') }}">
                    <i class="bi bi-flag{{ Route::is('reportdata.index') ? '-fill' : '' }}"></i>
                    Report
                </a>
            </li>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#dataManagerCollapse" role="button"
                aria-expanded="false" aria-controls="dataManagerCollapse">
                Manajer Billper Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="dataManagerCollapse">
                <ul class="list-unstyled">
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

                </ul>
            </div>

            <!-- Manajer Pra NPC Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#preNpcManagerCollapse" role="button"
                aria-expanded="false" aria-controls="preNpcManagerCollapse">
                Manajer Pra NPC
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="preNpcManagerCollapse">
                <ul class="list-unstyled">
                    <li class=" {{ Route::is('toolspranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('toolspranpc.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle {{ Route::is('toolspranpc.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li class=" {{ Route::is('pranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('pranpc.index') }}">
                            <i class="bi bi-clipboard2-data {{ Route::is('pranpc.index') ? '-fill' : '' }}"></i>
                            Data Pra NPC
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Profil Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#profileCollapse" role="button"
                aria-expanded="false" aria-controls="profileCollapse">
                Profil
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="profileCollapse">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('data-akun.index') ? 'active' : '' }}">
                        <a href="{{ route('data-akun.index') }}">
                            <i class="bi bi-people{{ Route::is('data-akun.index') ? '-fill' : '' }}"></i>
                            Akun
                        </a>
                    </li>
                </ul>
            </div>
        @elseif(auth()->user()->level == 'Admin Billper')
            <li class="{{ Route::is('adminbillper.index') ? 'active' : '' }}">
                <a href="{{ route('adminbillper.index') }}">
                    <i class="bi bi-grid{{ Route::is('adminbillper.index') ? '-fill' : '' }}"></i>
                    Dashboard Admin
                </a>
            </li>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#adminDataManagerCollapse" role="button"
                aria-expanded="false" aria-controls="adminDataManagerCollapse">
                Manajer Data
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="adminDataManagerCollapse">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('all-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('all-adminbillper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('all-adminbillper.index') ? '-fill' : '' }}"></i>
                            Data Plotting
                        </a>
                    </li>
                </ul>
            </div>
            <div class="collapse show" id="adminDataManagerCollapse">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('report-all-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('report-all-adminbillper.index') }}">
                            <i class="bi bi-flag{{ Route::is('report-all-adminbillper.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>
        @elseif(auth()->user()->level == 'Admin PraNPC')
            <li class="{{ Route::is('adminpranpc.index') ? 'active' : '' }}">
                <a href="{{ route('adminpranpc.index') }}">
                    <i class="bi bi-grid{{ Route::is('adminpranpc.index') ? '-fill' : '' }}"></i>
                    Dashboard Admin
                </a>
            </li>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#adminDataManagerCollapse1" role="button"
                aria-expanded="false" aria-controls="adminDataManagerCollapse1">
                Manajer Data Pranpc
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="adminDataManagerCollapse1">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('pranpc-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('pranpc-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('pranpc-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Data Plotting Pranpc
                        </a>
                    </li>
                </ul>
            </div>
            <div class="collapse show" id="adminDataManagerCollapse1">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('report-pranpc-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('report-pranpc-adminpranpc.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('report-pranpc-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Report Sales Pranpc
                        </a>
                    </li>
                </ul>
            </div>

            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#adminDataManagerCollapse2" role="button"
                aria-expanded="false" aria-controls="adminDataManagerCollapse2">
                Manajer Data Pranpc
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="adminDataManagerCollapse2">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('existing-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('existing-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Data Plotting Existing
                        </a>
                    </li>
                </ul>
            </div>
            <div class="collapse show" id="adminDataManagerCollapse2">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('report-existing-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('report-existing-adminpranpc.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('report-existing-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Report Sales Existing
                        </a>
                    </li>
                </ul>
            </div>
        @elseif(auth()->user()->level == 'User')
            <li class="{{ Route::is('user.index') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="bi bi-grid{{ Route::is('user.index') ? '-fill' : '' }}"></i>
                    Dashboard User
                </a>
            </li>
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#adminDataManagerCollapse1" role="button"
                aria-expanded="false" aria-controls="adminDataManagerCollapse1">
                Operasional Billper Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="adminDataManagerCollapse1">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('assignmentbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('assignmentbillper.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentbillper.index') ? '-fill' : '' }}"></i>
                            Assignment Billper Existing
                        </a>
                    </li>
                </ul>
            </div>

            <div class="collapse show" id="adminDataManagerCollapse1">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('reportassignmentbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentbillper.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportassignmentbillper.index') ? '-fill' : '' }}"></i>
                            Report Billper Existing
                        </a>
                    </li>
                </ul>
            </div>


            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#adminDataManagerCollapse2" role="button"
                aria-expanded="false" aria-controls="adminDataManagerCollapse2">
                Operasional Pranpc
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <div class="collapse show" id="adminDataManagerCollapse2">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('assignmentpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('assignmentpranpc.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentpranpc.index') ? '-fill' : '' }}"></i>
                            Assignment Pranpc
                        </a>
                    </li>
                </ul>
            </div>
            <div class="collapse show" id="adminDataManagerCollapse2">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('reportassignmentpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentpranpc.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportassignmentpranpc.index') ? '-fill' : '' }}"></i>
                            Report Pranpc
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </ul>
</div>

<!-- Tambahkan JavaScript untuk mengubah ikon saat collapse dibuka/tutup -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
        collapseElements.forEach(function(collapseElement) {
            collapseElement.addEventListener('click', function() {
                var target = document.querySelector(collapseElement.getAttribute('href'));
                var icon = collapseElement.querySelector('i.bi');
                target.addEventListener('shown.bs.collapse', function() {
                    icon.classList.remove('bi-chevron-right');
                    icon.classList.add('bi-chevron-down');
                });
                target.addEventListener('hidden.bs.collapse', function() {
                    icon.classList.remove('bi-chevron-down');
                    icon.classList.add('bi-chevron-right');
                });
            });
        });
    });
</script>
