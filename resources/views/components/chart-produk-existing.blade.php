<div class="head-chart text-center mb-3">
    <span class="fs-5" id="text-secondary">Existing</span>
</div>
<div class="d-flex align-items-center w-100">
    <div class="list-legends-existing d-flex flex-column w-50">
        <!-- Legend items will be injected here -->
    </div>
    <div id="chart-produk-existing" class="chart-dashboard-existing text-center mb-3 w-50">
        {{-- Chart --}}
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dataexisting = @json(array_values($existingProdukCounts));
        var labelsexisting = @json(array_keys($existingProdukCounts));

        // Nonaktifkan legend bawaan ApexCharts
        var optionsexisting = {
            series: dataexisting,
            chart: {
                type: 'pie',
                height: 250,
            },
            labels: labelsexisting,
            colors: ['#C8190B', '#DA665C', '#EDB2AE'],
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

        var chartexisting = new ApexCharts(document.querySelector("#chart-produk-existing"), optionsexisting);
        chartexisting.render();

        // Menambahkan custom legend ke dalam list-legends-existing
        var legendContainer = document.querySelector(".list-legends-existing");

        labelsexisting.forEach(function(label, index) {
            // Calculate the total only if the array is not empty, otherwise set total to 0
            var total = dataexisting.length > 0 ? dataexisting.reduce((a, b) => a + b, 0) : 0;

            // Calculate the percentage, handling cases where total is 0
            var percentage = total > 0 ? ((dataexisting[index] / total) * 100).toFixed(2) : 0;

            // Create a list element for the legend
            var legendItem = document.createElement('div');
            legendItem.classList.add('legend-item-existing');

            // Create a color box for the legend
            var colorBox = document.createElement('span');
            colorBox.classList.add('color-box-existing');
            colorBox.style.backgroundColor = optionsexisting.colors[index];

            // Create the label text with the percentage and value
            var labelText = document.createElement('span');
            labelText.innerHTML =
                `<strong style="font-family: Poppins, sans-serif;">${label}: (${percentage}%)</strong><br/><span class="text-secondary">${dataexisting[index]}</span>`;

            legendItem.appendChild(colorBox);
            legendItem.appendChild(labelText);

            legendContainer.appendChild(legendItem);
        });
    });
</script>
