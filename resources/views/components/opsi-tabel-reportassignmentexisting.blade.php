<div class="d-flex justify-content-left">
    <a href="{{ route('info-reportassignmentexisting', ['id' => $sales_report->id]) }}" class="btn border border-0">
        <div class="d-flex align-items-center text-blue">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
    </a>
    @if ($sales_report->billpers && $sales_report->billpers->status_pembayaran !== 'Paid')
        <button type="button" class="btn border border-0" onclick="confirmReset('{{ $sales_report->id }}')">
            <div class="d-flex align-items-center text-danger">
                <i class="bi bi-x-circle-fill fs-5"></i>
            </div>
        </button>
    @endif
    <form id="reset-form-{{ $sales_report->id }}"
        action="{{ route('reset-reportassignmentexisting', ['id' => $sales_report->id]) }}" method="POST"
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
