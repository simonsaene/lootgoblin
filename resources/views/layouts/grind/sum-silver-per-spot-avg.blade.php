<div class="mt-5">
    <h3 class="pb-2 border-bottom">Silver/h by Grind Spot</h3>
</div>
<div>
    <canvas id="silverPerSpotPerHourChart" height="100"></canvas>
</div>

@include('layouts.grind.chart-logic')

<script>

    var ctx = document.getElementById('silverPerSpotPerHourChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: grindSpotNames,
            datasets: [{
                label: 'Avg Silver Earned per Spot per Hour',
                barPercentage: 0.5,
                barThickness: 20,
                maxBarThickness: 25,
                data: avgSilverPerSpot,
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
                        var formattedValue = value.toFixed(2);
                        return parseFloat(formattedValue).toLocaleString() + ' silver/h';
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