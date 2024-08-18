<div class="d-flex justify-content-left">
    <a href="{{ route('info-reportassignmentpranpc', ['id' => $sales_report->id]) }}" class="btn border border-0">
        <div class="d-flex align-items-center text-blue">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
    </a>
    @if ($sales_report->pranpcs)
        @php
            // Ambil bulan dan tahun dari waktu_visit untuk membandingkan
            $currentMonthYear = \Carbon\Carbon::parse($sales_report->waktu_visit)->format('Y-m');

            // Cek apakah sudah ada Visit 2 di bulan dan tahun yang sama
            $hasVisit2 = \App\Models\SalesReport::where('pranpc_id', $sales_report->pranpc_id)
                ->where('jmlh_visit', 'Visit 2')
                ->where(\DB::raw("DATE_FORMAT(waktu_visit, '%Y-%m')"), $currentMonthYear)
                ->exists();
        @endphp

        {{-- Jika Visit 1 dan tidak ada Visit 2 di bulan yang sama, tampilkan tombol --}}
        @if ($sales_report->jmlh_visit === 'Visit 1' && !$hasVisit2 && $sales_report->pranpcs->status_pembayaran !== 'Paid')
            <button type="button" class="btn border border-0" onclick="confirmReset('{{ $sales_report->id }}')">
                <div class="d-flex align-items-center text-danger">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
            </button>
            {{-- Jika Visit 2, selalu tampilkan tombol --}}
        @elseif ($sales_report->jmlh_visit === 'Visit 2' && $sales_report->pranpcs->status_pembayaran !== 'Paid')
            <button type="button" class="btn border border-0" onclick="confirmReset('{{ $sales_report->id }}')">
                <div class="d-flex align-items-center text-danger">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
            </button>
        @endif
    @endif

    <form id="reset-form-{{ $sales_report->id }}"
        action="{{ route('reset-reportassignmentpranpc', ['id' => $sales_report->id]) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
</div>



<script>
    function confirmReset(id) {
        Swal.fire({
            title: 'Yakin Mereset data?',
            text: "Data yang tereset tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#831a16',
            cancelButtonColor: '#727375',
            confirmButtonText: 'Yes, reset it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reset-form-' + id).submit();
            }
        })
    }
</script>
