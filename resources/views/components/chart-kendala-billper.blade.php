<div class="head-chart text-center mb-3">
    <span class="text-blue fs-5">Billper</span>
</div>
<div id="chart-kendala-billper" class="chart-dashboard text-center mb-3">
    {{-- Chart --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dataBillper = @json($databillper);
        var labelsBillper = @json($labelbillper);

        if (dataBillper.length === 0) {
            // Menampilkan pesan jika data kosong
            document.querySelector("#chart-kendala-billper").innerHTML = 'Data Tidak Ada';
        } else {
            var optionsBillper = {
                series: dataBillper,
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: labelsBillper,
                colors: ['#0066CB', '#5599DC', '#AACCEE'], // Daftar warna sesuai urutan data
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

            var chartBillper = new ApexCharts(document.querySelector("#chart-kendala-billper"), optionsBillper);
            chartBillper.render();
        }
    });
</script>
