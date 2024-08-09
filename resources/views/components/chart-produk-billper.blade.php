<div class="head-chart text-center mb-3">
    <span class="text-blue fs-5">Billper</span>
</div>
<div class="d-flex align-items-center w-100">
    <div class="list-legends-billper d-flex flex-column w-50">
        <!-- Legend items will be injected here -->
    </div>
    <div id="chart-produk-billper" class="chart-dashboard-billper text-center mb-3 w-50">
        {{-- Chart --}}
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dataBillper = @json(array_values($billperProdukCounts));
        var labelsBillper = @json(array_keys($billperProdukCounts));

        // Nonaktifkan legend bawaan ApexCharts

        var optionsBillper = {
            series: dataBillper,
            chart: {
                type: 'pie',
                height: 250,
            },
            labels: labelsBillper,
            colors: ['#0066CB', '#5599DC', '#AACCEE'],
            legend: {
                show: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.globals.series[opts.seriesIndex]; // Menampilkan total count
                }
            }
        };

        var chartBillper = new ApexCharts(document.querySelector("#chart-produk-billper"), optionsBillper);
        chartBillper.render();

        // Menambahkan custom legend ke dalam list-legends-billper
        var legendContainer = document.querySelector(".list-legends-billper");

        labelsBillper.forEach(function(label, index) {
            // Calculate the total only if the array is not empty, otherwise set total to 0
            var total = dataBillper.length > 0 ? dataBillper.reduce((a, b) => a + b, 0) : 0;

            // Calculate the percentage, handling cases where total is 0
            var percentage = total > 0 ? ((dataBillper[index] / total) * 100).toFixed(2) : 0;

            // Create a list element for the legend
            var legendItem = document.createElement('div');
            legendItem.classList.add('legend-item-billper');

            // Create a color box for the legend
            var colorBox = document.createElement('span');
            colorBox.classList.add('color-box-billper');
            colorBox.style.backgroundColor = optionsBillper.colors[index];

            // Create the label text with the percentage and value
            var labelText = document.createElement('span');
            labelText.innerHTML =
                `<strong style="font-family: Poppins, sans-serif;">${label}: (${percentage}%)</strong><br/><span class="text-secondary">${dataBillper[index]}</span>`;

            legendItem.appendChild(colorBox);
            legendItem.appendChild(labelText);

            legendContainer.appendChild(legendItem);
        });
    });
</script>
