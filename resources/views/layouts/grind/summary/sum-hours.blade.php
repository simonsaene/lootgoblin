<div class="mt-5">
    <h3 class="pb-2 border-bottom">Total Hours by Grind Spot</h3>
</div>
<div>
    <canvas id="grindSpotBarChart" height="100"></canvas>
</div>

@include('layouts.grind.summary.chart-logic')

<script>
    var ctx = document.getElementById('grindSpotBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grindSpotNames,
            datasets: [{
                label: 'Hours Spent',
                barPercentage: 0.5,
                barThickness: 20,
                maxBarThickness: 25,
                data: hours,
                backgroundColor: datasetColors,
                borderColor: datasetColors.map(function(color) {
                    return chroma(color).brighten(1).hex();
                }),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                },
                datalabels: {
                    display: true,
                    color: 'white',
                    formatter: function(value) {
                        return value.toFixed(2) + ' hours';
                    },
                    anchor: 'end',
                    align: 'start',
                    offset: 0,
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    textStrokeColor: '#000',
                    textStrokeWidth: 3,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    display: false
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>