<div class="card-body">
    <div class="head-card mb-3">
        <span class="fs-5">Pelanggan Pranpc</span>
    </div>
    <div class="card-content mb-3 d-flex justify-content-end">
        <span class="fw-bold fs-2">
            {{ number_format($pranpcCurrentMonthCount, 0, ',', '.') }}
            <span class="fs-5">
                Pelanggan
            </span>
        </span>
    </div>
    <div class="footer-card d-flex justify-content-end">
        <span class="font-grey text-footer-card">
            <span class="fw-bold">
                {{ number_format($plottingpranpcCurrentMonthCount, 0, ',', '.') }}
            </span>
            Pelanggan Terplotting bulan saat ini.
        </span>
    </div>
</div>
