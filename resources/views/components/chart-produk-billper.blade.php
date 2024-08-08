<div class="head-chart text-center mb-3">
    <span class="text-blue fs-5">Billper</span>
</div>
<div id="chart-produk-billper" class="chart-dashboard text-center mb-3">
    {{-- Chart --}}
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dari PHP ke JavaScript
        var dataBillper = @json(array_values($billperProdukCounts));
        var labelsBillper = @json(array_keys($billperProdukCounts));

        if (dataBillper.length === 0) {
            // Menampilkan pesan jika data kosong
            document.querySelector("#chart-produk-billper").innerHTML = 'Data Tidak Ada';
        } else {
            // Opsi konfigurasi untuk chart donut
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
            var chartBillper = new ApexCharts(document.querySelector("#chart-produk-billper"),
                optionsBillper);
            chartBillper.render();
        }
    });
</script>
