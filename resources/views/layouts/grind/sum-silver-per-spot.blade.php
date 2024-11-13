<div class="card" style="width: 100%; max-width: 100%; margin: 5px auto;">
    <div class="card-header">
        Total Silver by Grind Spot
    </div>
    <div class="card-body">
        <canvas id="silverPerSpotChart" height="100"></canvas>
    </div>
</div>

@include('layouts.grind.chart-logic')

<script>

    var ctx = document.getElementById('silverPerSpotChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: grindSpots,
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
                    }
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