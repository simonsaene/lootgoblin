<div class="mt-5">
    <h3 class="pb-2 border-bottom">Grind Spot Distribution</h3>
</div>
<div>
    <canvas id="grindSpotPieChart" height="50"></canvas>
</div>

@include('layouts.grind.chart-logic')

<script>
    var ctx = document.getElementById('grindSpotPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: grindSpotNames,
            datasets: [{
                label: 'Grind Spot Frequency',
                data: spotCount,
                backgroundColor: datasetColors,
                borderColor: datasetColors.map(function(color) {
                    return chroma(color).brighten(1).hex();
                }),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            var label = tooltipItem.label || '';
                            if (label) {
                                label += ': ' + tooltipItem.raw + ' sessions';
                            }
                            return label;
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: '#fff',
                    formatter: function(value, ctx) {
                        var label = ctx.chart.data.labels[ctx.dataIndex];
                        return label + ': ' + value;
                    },
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    textStrokeColor: '#000',
                    textStrokeWidth: 3,
                    anchor: 'center',
                    align: 'center',
                }
            }
        },
        plugins: [ChartDataLabels] 
    });
</script>