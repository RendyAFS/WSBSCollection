<div class="head-chart text-center mb-3">
    <span class="fs-5" id="text-secondary">Existing</span>
</div>
<div id="chart-produk-existing" class="chart-dashboard text-center mb-3">
    {{-- Chart --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dari PHP ke JavaScript
        var dataExisting = @json(array_values($existingProdukCounts));
        var labelsExisting = @json(array_keys($existingProdukCounts));

        if (dataExisting.length === 0) {
            // Menampilkan pesan jika data kosong
            document.querySelector("#chart-produk-existing").innerHTML = 'Data Tidak Ada';
        } else {
            // Opsi konfigurasi untuk chart donut
            var optionsExisting = {
                series: dataExisting,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: labelsExisting,
                colors: ['#C8190B', '#DA665C', '#EDB2AE'], // Daftar warna sesuai urutan data
                dataLabels: {
                    enabled: true
                },
                legend: {
                    position: 'left',
                    formatter: function(seriesName, opts) {
                        // Menambahkan count ke legend
                        return seriesName + ': ' + opts.w.globals.series[opts.seriesIndex];
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%'
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };

            // Membuat dan merender chart
            var chartExisting = new ApexCharts(document.querySelector("#chart-produk-existing"),
                optionsExisting);
            chartExisting.render();
        }
    });
</script>
