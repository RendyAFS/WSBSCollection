@extends('layouts.app-super-admin')

@section('content')
    <div class="px-3 py-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <span class="fw-bold fs-2">
                Grafik Data Existing
            </span>
            <div class="d-flex">
                <!-- Button Filter modal -->
                <button type="button" class="btn btn-white me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>

                <a href="{{ route('reportdataexisting.index') }}" class="btn btn-green ">
                    <i class="bi bi-table"></i> Table
                </a>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <form action="{{ route('grafikdataexisting.index') }}" method="GET">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Filter Grafik</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="year" class="mr-2">Pilih Tahun:</label>
                                        <select name="year" id="year" class="form-select">
                                            @for ($i = date('Y'); $i >= 2000; $i--)
                                                <option value="{{ $i }}"
                                                    {{ request('year') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="jenis_grafik" class="mr-2">Pilih Jenis Grafik:</label>
                                        <select name="jenis_grafik" id="jenis_grafik" class="form-select">
                                            <option value="Billing"
                                                {{ request('jenis_grafik') == 'Billing' ? 'selected' : '' }}>Billing
                                            </option>
                                            <option value="SSL" {{ request('jenis_grafik') == 'SSL' ? 'selected' : '' }}>
                                                SSL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-grey" data-bs-dismiss="modal">Batal</button>
                                    <button type="Submit" class="btn btn-secondary" data-bs-dismiss="modal">Filter
                                        Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="chartpelangganexisting"></div>
@endsection

@push('scripts')
    <script type="module">
        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            const numberString = angka.toString().replace(/[^,\d]/g, '');
            const split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        // Fungsi untuk format persen
        function formatPercent(value) {
            return value.toFixed(2) + '%'; // Format angka dengan 2 desimal dan tambahkan persen
        }
        // Chart data from the server
        var categories = @json($chartData['categories']);
        var totalData = @json($chartData['total_ssl'] ?? ($chartData['total'] ?? []));
        var paidData = @json($chartData['total_paid'] ?? ($chartData['paid'] ?? []));
        var unpaidData = @json($chartData['total_unpaid'] ?? ($chartData['unpaid'] ?? []));
        var pendingData = @json($chartData['total_pending'] ?? ($chartData['pending'] ?? []));
        var billingData = @json($chartData['billing'] ?? []);

        // Mendapatkan jenis grafik
        var jenisGrafik = @json($jenisGrafik);

        // Tentukan nama seri dan data berdasarkan jenis grafik
        var progressName = jenisGrafik === 'SSL' ? 'Progress SSL' : 'Progress Billing';
        var totalName = jenisGrafik === 'SSL' ? 'Total SSL' : 'Total Billing';
        var totalSeriesData = jenisGrafik === 'SSL' ? totalData : billingData;


        // Chart options
        var options = {
            series: [{
                name: progressName,
                type: 'line',
                color: '#000000',
                data: billingData,
                yAxis: 1
            }, {
                name: totalName,
                type: 'column',
                color: '#0D6EFD',
                data: totalData
            }, {
                name: 'Paid',
                type: 'column',
                color: '#26d13c',
                data: paidData
            }, {
                name: 'Unpaid',
                type: 'column',
                color: '#FFC107',
                data: unpaidData
            }, {
                name: 'Pending',
                type: 'column',
                color: '#6C757D',
                data: pendingData,
            }],
            chart: {
                height: 750,
                type: 'line',
                stacked: false
            },
            title: {
                text: `Grafik Data Pembayaran - ${jenisGrafik}`
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    if (opts.seriesIndex === 0) { // Index 0 is for Progress Billing
                        return val === 0 ? '' : formatPercent(val); // Jangan tampilkan nilai jika 0
                    } else {
                        return ''; // Tidak tampilkan dataLabels untuk seri lainnya
                    }
                },
                style: {
                    colors: ['#000000']
                }
            },
            markers: {
                size: [5, 0, 0, 0, 0], // Hanya tampilkan dot untuk Progress Billing
                colors: ['#000000', '#0D6EFD', '#26d13c', '#FFC107', '#6C757D'], // Warna dot untuk masing-masing seri
                strokeColors: ['#000000'], // Warna border dot untuk Progress Billing
                strokeWidth: 2,
                hover: {
                    size: 7
                }
            },
            stroke: {
                width: [4, 1, 1, 1, 1]
            },
            xaxis: {
                categories: categories,
            },
            yaxis: [{
                title: {
                    text: 'Persentase'
                },
                labels: {
                    formatter: function(value) {
                        return formatPercent(value);
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: jenisGrafik === 'SSL' ? 'SSL' : 'Rupiah'
                },
                labels: {
                    formatter: function(value) {
                        return jenisGrafik === 'SSL' ? value : formatRupiah(value);
                    }
                }
            }],
            tooltip: {
                y: [{
                        formatter: function(value) {
                            return formatPercent(value); // Progress Billing in percent
                        }
                    },
                    {
                        formatter: function(value) {
                            return jenisGrafik === 'SSL' ? value : formatRupiah(
                                value); // Total Billing in Rupiah
                        }
                    },
                    {
                        formatter: function(value) {
                            return jenisGrafik === 'SSL' ? value : formatRupiah(value); // Paid in Rupiah
                        }
                    },
                    {
                        formatter: function(value) {
                            return jenisGrafik === 'SSL' ? value : formatRupiah(value); // Unpaid in Rupiah
                        }
                    },
                    {
                        formatter: function(value) {
                            return jenisGrafik === 'SSL' ? value : formatRupiah(value); // Pending in Rupiah
                        }
                    }
                ]
            },
            legend: {
                horizontalAlign: 'left',
                offsetX: 40
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartpelangganexisting"), options);
        chart.render();
    </script>
@endpush
