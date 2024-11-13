<div class="card" style="width: 100%; max-width: 100%; margin: 5px auto;">
    <div class="card-header">
        Grind Spot Distribution
    </div>
    <div class="card-body">
        <canvas id="grindSpotPieChart" height="50"></canvas>
    </div>
</div>

@include('layouts.grind.chart-logic')

<script>
    var ctx = document.getElementById('grindSpotPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: grindSpots,
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
                    position: 'top',
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
                }
            }
        }
    });
</script>