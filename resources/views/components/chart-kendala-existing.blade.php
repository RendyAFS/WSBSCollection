<div class="head-chart text-center mb-3">
    <span class="fs-5" id="text-secondary">Existing</span>
</div>
<div id="chart-kendala-existing" class="chart-dashboard text-center mb-3">
    {{-- Chart --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dataExisting = @json($dataexisting);
        var labelsExisting = @json($labelexisting);

        if (dataExisting.length === 0) {
            // Menampilkan pesan jika data kosong
            document.querySelector("#chart-kendala-existing").innerHTML = 'Data Tidak Ada';
        } else {
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
                    position: 'bottom'
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

            var chartExisting = new ApexCharts(document.querySelector("#chart-kendala-existing"), optionsExisting);
            chartExisting.render();
        }
    });
</script>
