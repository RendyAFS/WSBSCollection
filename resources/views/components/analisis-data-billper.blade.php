<div class="card-body">
    <div class="head-card mb-3">
        <span class="fs-5">Pelanggan Billper</span>
    </div>
    <div class="card-content mb-3 d-flex justify-content-end">
        <span class="fw-bold fs-2">
            {{ number_format($billperCount, 0, ',', '.') }}
            <span class="fs-5">
                Pelanggan
            </span>
        </span>
    </div>
    <div class="footer-card d-flex justify-content-end">
        <span
            class="percent-footer-card me-2 fw-bold {{ $percentBillper > 0 ? 'text-success' : ($percentBillper < 0 ? 'text-danger' : 'text-secondary') }}">
            {{-- persen --}}
            @if ($percentBillper > 0)
                <i class="bi bi-arrow-up"></i>
            @elseif ($percentBillper < 0)
                <i class="bi bi-arrow-down"></i>
            @endif
            {{ number_format(abs($percentBillper), 2, ',', '.') }}%
        </span>
        <span class="font-grey text-footer-card">
            dari bulan sebelumnya.
        </span>
    </div>
</div>
