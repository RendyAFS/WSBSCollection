<div class="d-flex justify-content-between align-items-center">
    <span class="text-blue">
        Billper
    </span>
    <a href="{{ route('reportsalesbillper.index') }}" class="">
        <i class="bi bi-three-dots fw-bold fs-4 text-dark"></i>
    </a>
</div>

<div class="contain-progress-sales">
    @foreach ($salesbillperterendah as $sale)
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="w-25 text-center text-secondary">{{ $sale->name }}</span>
            <div class="progress w-50" role="progressbar" aria-label="Example with label"
                 aria-valuenow="{{ $sale->wo_sudah_visit }}" aria-valuemin="0" aria-valuemax="100">
                @php
                    $progress = ($sale->wo_sudah_visit / ($sale->total_assignment ?: 1)) * 100;
                @endphp
                <div class="progress-bar" id="bg-blue" style="width: {{ $progress }}%">{{ round($progress, 2) }}%</div>
            </div>
            <span class="w-25 text-center text-secondary">{{ $sale->wo_sudah_visit }} Visit</span>
        </div>
    @endforeach
</div>
