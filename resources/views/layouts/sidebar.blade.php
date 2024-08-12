<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center mt-3">
        <img src="{{ asset('storage/file_assets/logo-telkom2.png') }}" alt="" id="side-logo-telkom">
        <span class="fs-4 fw-bold text-white">Data Collection</span>
    </div>
    <ul class="list-unstyled components p-4">
        @if (auth()->user()->level == 'Super Admin')
            <li class="{{ Route::is('super-admin.index') ? 'active' : '' }}">
                <a href="{{ route('super-admin.index') }}" class="fw-bold">
                    <i class="bi bi-grid{{ Route::is('super-admin.index') ? '-fill' : '' }}"></i>
                    Dashboard
                </a>
            </li>

            <li
                class="{{ Route::is('datamaster.index') || Route::is('edit-datamasters') || Route::is('previewdatamaster.index') || Route::is('edit-tempdatamasters') ? 'active' : '' }}">
                <a href="{{ route('datamaster.index') }}" class="fw-bold">
                    <i
                        class="bi bi-database{{ Route::is('datamaster.index') || Route::is('edit-datamasters') || Route::is('previewdatamaster.index') || Route::is('edit-tempdatamasters') ? '-fill' : '' }}"></i>
                    Data Master
                </a>
            </li>

            <!-- Manajer Data bilper Collapse Group -->
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatabillpersuperadmin" role="button"
                aria-expanded="false" aria-controls="manajerdatabillpersuperadmin">
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Billper
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('toolsbillper.index') || Route::is('billper.index') || Route::is('billperriwayat.index') || Route::is('edit-billpers') || Route::is('reportdatabillper.index') || Route::is('grafikdatabillper.index') || Route::is('reportsalesbillper.index') ? 'show' : '' }} "
                id="manajerdatabillpersuperadmin">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('toolsbillper.index') ? 'active' : '' }}">
                        <a href="{{ route('toolsbillper.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle{{ Route::is('toolsbillper.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li
                        class="{{ Route::is('billper.index') || Route::is('edit-billpers') || Route::is('billperriwayat.index') ? 'active' : '' }}">
                        <a href="{{ route('billper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('billper.index') || Route::is('edit-billpers') || Route::is('billperriwayat.index') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Existing
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('toolsexisting.index') || Route::is('existing.index') || Route::is('existingriwayat.index') || Route::is('edit-existings') || Route::is('reportdataexisting.index') || Route::is('grafikdataexisting.index') || Route::is('reportsalesexisting.index') ? 'show' : '' }}"
                id="manajerdataexistingsuperadmin">
                <ul class="list-unstyled">
                    <li class="{{ Route::is('toolsexisting.index') ? 'active' : '' }}">
                        <a href="{{ route('toolsexisting.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle{{ Route::is('toolsexisting.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li
                        class="{{ Route::is('existing.index') || Route::is('edit-existings') || Route::is('existingriwayat.index') ? 'active' : '' }}">
                        <a href="{{ route('existing.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing.index') || Route::is('edit-existings') || Route::is('existingriwayat.index') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Pra NPC
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('toolspranpc.index') || Route::is('pranpc.index') || Route::is('pranpcriwayat.index') || Route::is('edit-pranpcs') || Route::is('reportdatapranpc.index') || Route::is('reportsalespranpc.index') ? 'show' : '' }}"
                id="manajerdatapranpcsuperadmin">
                <ul class="list-unstyled">
                    <li class=" {{ Route::is('toolspranpc.index') ? 'active' : '' }}">
                        <a href="{{ route('toolspranpc.index') }}">
                            <i
                                class="bi bi-wrench-adjustable-circle {{ Route::is('toolspranpc.index') ? '-fill' : '' }}"></i>
                            Tool
                        </a>
                    </li>
                    <li
                        class=" {{ Route::is('pranpc.index') || Route::is('edit-pranpcs') || Route::is('pranpcriwayat.index') ? 'active' : '' }}">
                        <a href="{{ route('pranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('pranpc.index') || Route::is('edit-pranpcs') || Route::is('pranpcriwayat.index') ? '-fill' : '' }}"></i>
                            Data
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
                <i class="bi bi-chevron-right me-2"></i>
                Profil
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('data-akun.index') ? 'show' : '' }}" id="manajerdataprofilesuperadmin">
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
                <a href="{{ route('adminbillper.index') }}" class="fw-bold">
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Data Billper
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('billper-adminbillper.index') || Route::is('report-billper-adminbillper.index') || Route::is('edit-billpersadminbillper') ? 'show' : '' }}"
                id="manajerdatabillperadminbillper">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('billper-adminbillper.index') || Route::is('edit-billpersadminbillper') ? 'active' : '' }}">
                        <a href="{{ route('billper-adminbillper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('billper-adminbillper.index') || Route::is('edit-billpersadminbillper') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Data Existing
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('existing-adminbillper.index') || Route::is('report-existing-adminbillper.index') || Route::is('edit-existingsadminbillper') ? 'show' : '' }}"
                id="manajerdataexistingadminbillper">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('existing-adminbillper.index') || Route::is('edit-existingsadminbillper') ? 'active' : '' }}">
                        <a href="{{ route('existing-adminbillper.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing-adminbillper.index') || Route::is('edit-existingsadminbillper') ? '-fill' : '' }}"></i>
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
        @elseif(auth()->user()->level == 'Admin Pranpc')
            <li class="{{ Route::is('adminpranpc.index') ? 'active' : '' }}">
                <a href="{{ route('adminpranpc.index') }}" class="fw-bold">
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Data Pranpc
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('pranpc-adminpranpc.index') || Route::is('report-pranpc-adminpranpc.index') || Route::is('edit-pranpcsadminpranpc') ? 'show' : '' }}"
                id="manajerdatapranpcadminpranpc">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('pranpc-adminpranpc.index') || Route::is('edit-pranpcsadminpranpc') ? 'active' : '' }}">
                        <a href="{{ route('pranpc-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('pranpc-adminpranpc.index') || Route::is('edit-pranpcsadminpranpc') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Manajer Data Existing
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('existing-adminpranpc.index') || Route::is('report-existing-adminpranpc.index') || Route::is('edit-existingsadminpranpc') ? 'show' : '' }}"
                id="manajerdataexistingadminpranpc">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('existing-adminpranpc.index') || Route::is('edit-existingsadminpranpc') ? 'active' : '' }}">
                        <a href="{{ route('existing-adminpranpc.index') }}">
                            <i
                                class="bi bi-clipboard2-data{{ Route::is('existing-adminpranpc.index') || Route::is('edit-existingsadminpranpc') ? '-fill' : '' }}"></i>
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
        @elseif(auth()->user()->level == 'Sales')
            <li class="{{ Route::is('user.index') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="fw-bold">
                    <i class="bi bi-grid{{ Route::is('user.index') ? '-fill' : '' }}"></i>
                    Dashboard
                </a>
            </li>
            <div class="my-4">
                {{-- diver --}}
            </div>
            <span class="fw-bold d-block" data-bs-toggle="collapse" href="#manajerdatabillperuser" role="button"
                aria-expanded="false" aria-controls="manajerdatabillperuser">
                <i class="bi bi-chevron-right me-2"></i>
                Operasional Billper
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('assignmentbillper.index') || Route::is('reportassignmentbillper.index') || Route::is('info-assignmentbillper') || Route::is('info-reportassignmentbillper') ? 'show' : '' }}"
                id="manajerdatabillperuser">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('assignmentbillper.index') || Route::is('info-assignmentbillper') ? 'active' : '' }}">
                        <a href="{{ route('assignmentbillper.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentbillper.index') || Route::is('info-assignmentbillper') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>

                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('reportassignmentbillper.index') || Route::is('info-reportassignmentbillper') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentbillper.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('reportassignmentbillper.index') || Route::is('info-reportassignmentbillper') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Operasional Existing
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('assignmentexisting.index') || Route::is('reportassignmentexisting.index') || Route::is('info-assignmentexisting') || Route::is('info-reportassignmentexisting') ? 'show' : '' }}"
                id="manajerdataexistinguser">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('assignmentexisting.index') || Route::is('info-assignmentexisting') ? 'active' : '' }}">
                        <a href="{{ route('assignmentexisting.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentexisting.index') || Route::is('info-assignmentexisting') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>

                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('reportassignmentexisting.index') || Route::is('info-reportassignmentexisting') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentexisting.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('reportassignmentexisting.index') || Route::is('info-reportassignmentexisting') ? '-fill' : '' }}"></i>
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
                <i class="bi bi-chevron-right me-2"></i>
                Operasional Pranpc
            </span>
            <hr class="border border-white my-1">
            <div class="collapse {{ Route::is('assignmentpranpc.index') || Route::is('reportassignmentpranpc.index') || Route::is('info-assignmentpranpc') || Route::is('info-reportassignmentpranpc') ? 'show' : '' }}"
                id="manajerdatapranpcperuser">
                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('assignmentpranpc.index') || Route::is('info-assignmentpranpc') ? 'active' : '' }}">
                        <a href="{{ route('assignmentpranpc.index') }}">
                            <i
                                class="bi bi-clipboard-check{{ Route::is('assignmentpranpc.index') || Route::is('info-assignmentpranpc') ? '-fill' : '' }}"></i>
                            Assignment
                        </a>
                    </li>
                </ul>

                <ul class="list-unstyled">
                    <li
                        class="{{ Route::is('reportassignmentpranpc.index') || Route::is('info-reportassignmentpranpc') ? 'active' : '' }}">
                        <a href="{{ route('reportassignmentpranpc.index') }}">
                            <i
                                class="bi bi-flag{{ Route::is('reportassignmentpranpc.index') || Route::is('info-reportassignmentpranpc') ? '-fill' : '' }}"></i>
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
            var target = document.querySelector(collapseElement.getAttribute('href'));
            var icon = collapseElement.querySelector('i.bi');

            // Check if the target collapse is already shown (active route)
            if (target.classList.contains('show')) {
                icon.classList.remove('bi-chevron-right');
                icon.classList.add('bi-chevron-down');
            }

            collapseElement.addEventListener('click', function() {
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
