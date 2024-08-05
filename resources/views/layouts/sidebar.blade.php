<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <img src="{{ asset('storage/file_assets/logo-telkom2.png') }}" alt="" id="side-logo-telkom">
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

            <li class="{{ Route::is('datamaster.index') || Route::is('edit-datamasters') || Route::is('previewdatamaster.index') || Route::is('edit-tempdatamasters') ? 'active' : '' }}">
                <a href="{{ route('datamaster.index') }}">
                    <i class="bi bi-database{{ Route::is('datamaster.index') || Route::is('edit-datamasters') || Route::is('previewdatamaster.index') || Route::is('edit-tempdatamasters') ? '-fill' : '' }}"></i>
                    Data Master
                </a>
            </li>

            <!-- Manajer Data bilper Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatabillpersuperadmin" role="button"
                aria-expanded="false" aria-controls="manajerdatabillpersuperadmin">
                Manajer Billper
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatabillpersuperadmin">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('toolsbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('toolsbillper.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle{{ Route::is('toolsbillper.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li class="{{ Route::is('billper.index') || Route::is('edit-billpers') ? 'active' : '' }}">
                        <a href="{{ route('billper.index') }}">
                            <i class="bi bi-clipboard2-data{{ Route::is('billper.index') || Route::is('edit-billpers') ? '-fill' : '' }}"></i>
                            Data
                        </a>
                    </li>
                    <li
                        class="{{ Route::is('reportdatabillper.index') || Route::is('grafikdatabillper.index') ? 'active' : '' }}">
                        <a href="{{ route('reportdatabillper.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('reportdatabillper.index') || Route::is('grafikdatabillper.index') ? '-fill' : '' }}"></i>
                            Report Pelanggan
                        </a>
                    </li>

                    <li class="{{ Route::is('reportsalesbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('reportsalesbillper.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportsalesbillper.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Manajer Data Existing Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdataexistingsuperadmin" role="button"
                aria-expanded="false" aria-controls="manajerdataexistingsuperadmin">
                Manajer Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdataexistingsuperadmin">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('toolsexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('toolsexisting.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle{{ Route::is('toolsexisting.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li class="{{ Route::is('existing.index') || Route::is('edit-existings') ? 'active' : '' }}">
                        <a href="{{ route('existing.index') }}">
                            <i class="bi bi-clipboard2-data{{ Route::is('existing.index') || Route::is('edit-existings') ? '-fill' : '' }}"></i>
                            Data
                        </a>
                    </li>
                    <li
                        class="{{ Route::is('reportdataexisting.index') || Route::is('grafikdataexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('reportdataexisting.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('reportdataexisting.index') || Route::is('grafikdataexisting.index') ? '-fill' : '' }}"></i>
                            Report Pelanggan
                        </a>
                    </li>

                    <li class="{{ Route::is('reportsalesexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('reportsalesexisting.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportsalesexisting.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Manajer Pra NPC Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatapranpcsuperadmin" role="button"
                aria-expanded="false" aria-controls="manajerdatapranpcsuperadmin">
                Manajer Pra NPC
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatapranpcsuperadmin">
                <ul class="list-unstyled">
                    <li class=" {{ Route::is('toolspranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('toolspranpc.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle {{ Route::is('toolspranpc.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li class=" {{ Route::is('pranpc.index') || Route::is('edit-pranpcs') ? 'active' : '' }}">
                        <a href="{{ route('pranpc.index') }}">
                            <i class="bi bi-clipboard2-data {{ Route::is('pranpc.index') || Route::is('edit-pranpcs') ? '-fill' : '' }}"></i>
                            Data Pra NPC
                        </a>
                    </li>
                    <li class="{{ Route::is('reportdatapranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('reportdatapranpc.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportdatapranpc.index') ? '-fill' : '' }}"></i>
                            Report Pelanggan
                        </a>
                    </li>

                    <li class="{{ Route::is('reportsalespranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('reportsalespranpc.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportsalespranpc.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Profil Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdataprofilesuperadmin" role="button"
                aria-expanded="false" aria-controls="manajerdataprofilesuperadmin">
                Profil
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdataprofilesuperadmin">
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
                    Dashboard
                </a>
            </li>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatabillperadminbillper"
                role="button" aria-expanded="false" aria-controls="manajerdatabillperadminbillper">
                Manajer Data Billper
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatabillperadminbillper">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('billper-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('billper-adminbillper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('billper-adminbillper.index') ? '-fill' : '' }}"></i>
                            Data Plotting
                        </a>
                    </li>
                </ul>
                <ul class="list-unstyled">
                    <li class="{{ Route::is('report-billper-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('report-billper-adminbillper.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('report-billper-adminbillper.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdataexistingadminbillper"
                role="button" aria-expanded="false" aria-controls="manajerdataexistingadminbillper">
                Manajer Data Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdataexistingadminbillper">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('existing-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('existing-adminbillper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing-adminbillper.index') ? '-fill' : '' }}"></i>
                            Data Plotting
                        </a>
                    </li>
                </ul>
                <ul class="list-unstyled">
                    <li class="{{ Route::is('report-existing-adminbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('report-existing-adminbillper.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('report-existing-adminbillper.index') ? '-fill' : '' }}"></i>
                            Report Sales
                        </a>
                    </li>
                </ul>
            </div>
        @elseif(auth()->user()->level == 'Admin PraNPC')
            <li class="{{ Route::is('adminpranpc.index') ? 'active' : '' }}">
                <a href="{{ route('adminpranpc.index') }}">
                    <i class="bi bi-grid{{ Route::is('adminpranpc.index') ? '-fill' : '' }}"></i>
                    Dashboard
                </a>
            </li>

            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatapranpcadminpranpc"
                role="button" aria-expanded="false" aria-controls="manajerdatapranpcadminpranpc">
                Manajer Data Pranpc
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatapranpcadminpranpc">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('pranpc-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('pranpc-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('pranpc-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Data Plotting Pranpc
                        </a>
                    </li>
                </ul>
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
            <!-- Manajer Data Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdataexistingadminpranpc"
                role="button" aria-expanded="false" aria-controls="manajerdataexistingadminpranpc">
                Manajer Data Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdataexistingadminpranpc">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('existing-adminpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('existing-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing-adminpranpc.index') ? '-fill' : '' }}"></i>
                            Data Plotting Existing
                        </a>
                    </li>
                </ul>
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
                    Dashboard
                </a>
            </li>
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatabillperuser" role="button"
                aria-expanded="false" aria-controls="manajerdatabillperuser">
                Operasional Billper
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatabillperuser">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('assignmentbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('assignmentbillper.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentbillper.index') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>

                <ul class="list-unstyled">
                    <li class="{{ Route::is('reportassignmentbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentbillper.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportassignmentbillper.index') ? '-fill' : '' }}"></i>
                            Report
                        </a>
                    </li>
                </ul>
            </div>

            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdataexistinguser" role="button"
                aria-expanded="false" aria-controls="manajerdataexistinguser">
                Operasional Existing
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdataexistinguser">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('assignmentexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('assignmentexisting.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentexisting.index') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>

                <ul class="list-unstyled">
                    <li class="{{ Route::is('reportassignmentexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentexisting.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportassignmentexisting.index') ? '-fill' : '' }}"></i>
                            Report
                        </a>
                    </li>
                </ul>
            </div>


            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatapranpcperuser" role="button"
                aria-expanded="false" aria-controls="manajerdatapranpcperuser">
                Operasional Pranpc
                <i class="bi bi-chevron-down float-end"></i>
            </span>
            <hr class="border border-white my-1">
            <div class="collapse show" id="manajerdatapranpcperuser">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('assignmentpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('assignmentpranpc.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentpranpc.index') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>
                <ul class="list-unstyled">
                    <li class="{{ Route::is('reportassignmentpranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentpranpc.index') }}">
                            <i class="bi bi-flag{{ Route::is('reportassignmentpranpc.index') ? '-fill' : '' }}"></i>
                            Report
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
