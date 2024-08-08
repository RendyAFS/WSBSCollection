<div class="head-chart text-center mb-3">
    <span class="text-green fs-5">Pranpc</span>
</div>
<div id="chart-kendala-pranpc" class="chart-dashboard text-center mb-3">
    {{-- Chart --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dataPranpc = @json($datapranpc);
        var labelsPranpc = @json($labelpranpc);

        if (dataPranpc.length === 0) {
            // Menampilkan pesan jika data kosong
            document.querySelector("#chart-kendala-pranpc").innerHTML = 'Data Tidak Ada';
        } else {
            var optionsPranpc = {
                series: dataPranpc,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: labelsPranpc,
                colors: ['#4CB040', '#87CB7F', '#C3E5BF'], // Daftar warna sesuai urutan data
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

            var chartPranpc = new ApexCharts(document.querySelector("#chart-kendala-pranpc"),
                optionsPranpc);
            chartPranpc.render();
        }
    });
</script>
