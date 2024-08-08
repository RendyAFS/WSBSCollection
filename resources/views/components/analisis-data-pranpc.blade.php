<div class="card-body">
    <div class="head-card mb-3">
        <span class="fs-5">Data Master</span>
    </div>
    <div class="card-content mb-3 d-flex justify-content-end">
        <span class="fw-bold fs-2">
            {{ number_format($dataMasterCount, 0, ',', '.') }}
            <span class="fs-5">
                Pelanggan
            </span>
        </span>
    </div>
    <div class="footer-card d-flex justify-content-end">
        <span
            class="percent-footer-card me-2 fw-bold {{ $percentDataMaster > 0 ? 'text-success' : ($percentDataMaster < 0 ? 'text-danger' : 'text-secondary') }}">
            {{-- persen --}}
            @if ($percentDataMaster > 0)
                <i class="bi bi-arrow-up"></i>
            @elseif ($percentDataMaster < 0)
                <i class="bi bi-arrow-down"></i>
            @endif
            @php
                function formatNumber($number, $decimal = 2)
                {
                    $number = abs($number);
                    if ($number >= 1000 && $number < 1000000) {
                        return number_format($number / 1000, $decimal) . 'K';
                    } elseif ($number >= 1000000 && $number < 1000000000) {
                        return number_format($number / 1000000, $decimal) . 'M';
                    } elseif ($number >= 1000000000) {
                        return number_format($number / 1000000000, $decimal) . 'B';
                    }
                    return number_format($number, $decimal, ',', '.');
                }
            @endphp
            {{ formatNumber($percentDataMaster, 2) }}%
        </span>
        <span class="font-grey text-footer-card">
            dari bulan sebelumnya.
        </span>
    </div>
</div>
