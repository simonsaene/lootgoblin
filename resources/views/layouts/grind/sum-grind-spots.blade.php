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
                    display: true,  // Show data labels
                    color: '#fff',  // Label color (white text)
                    formatter: function(value, ctx) {
                        var label = ctx.chart.data.labels[ctx.dataIndex];  // Get the label (grind spot name)
                        return label + ': ' + value;  // Display the name and value (sessions)
                    },
                    font: {
                        size: 14,  // Font size for labels
                        weight: 'bold'  // Make the label text bold
                    },
                    textStrokeColor: '#000',  // Outline color (black)
                    textStrokeWidth: 3, // Set the width of the outline (adjust as needed)
                    anchor: 'center',  // Position the label at the end of the slice (outside)
                    align: 'center',  // Align the label to the left (outside the slice)
                }
            }
        },
        plugins: [ChartDataLabels] 
    });
</script>