<div class="mt-5">
    <h3 class="pb-2 border-bottom">Total Silver by Grind Spot</h3>
</div>
<div>
    <canvas id="silverPerSpotChart" height="100"></canvas>
</div>


@include('layouts.grind.summary.chart-logic')

<script>

    var ctx = document.getElementById('silverPerSpotChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: grindSpotNames,
            datasets: [{
                label: 'Silver Per Spot',
                barPercentage: 0.5,
                barThickness: 20,
                maxBarThickness: 25,
                data: silverPerSpot,
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
                        return value.toLocaleString() + ' silver/spot'; 
                    },
                    anchor: 'end',
                    align: 'start',
                    offset: 0,
                    font: {
                        size: 12,
                        weight: 'bold',
                        strokeStyle: 'black',
                        lineWidth: 2
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