<div class="card" style="width: 100%; max-width: 100%; margin: 5px auto;">
    <div class="card-header">
        Silver/h by Grind Spot
    </div>
    <div class="card-body">
        <canvas id="silverPerSpotPerHourChart" height="100"></canvas>
    </div>
</div>

@include('layouts.grind.chart-logic')

<script>

    var ctx = document.getElementById('silverPerSpotPerHourChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: grindSpots,
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
            indexAxis: 'y', // Make it a horizontal bar chart
            plugins: {
                legend: {
                    display: false // Hide the legend
                },
                tooltip: {
                    enabled: false // Optional: Disable the tooltip
                },
                datalabels: {
                    display: true, // Show the data labels
                    color: 'white',
                    formatter: function(value) {
                        // Round to 2 decimal places and add comma separator
                        var formattedValue = value.toFixed(2); // Limit to 2 decimals
                        return parseFloat(formattedValue).toLocaleString() + ' silver/h'; // Add comma separator and 'silver/h'
                    },
                    anchor: 'end', // Position the label at the end of the bar
                    align: 'start', // Align the label to the right end of the bar
                    offset: 0, // Position the label slightly outside the bar
                    font: {
                        size: 12, // Adjust the font size
                        weight: 'bold' // Make the label text bold
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Make sure the y-axis starts at 0
                },
                x: {
                    display: false // Hide the x-axis labels (hours values)
                }
            }
        },
        plugins: [ChartDataLabels] // Include the ChartDataLabels plugin
    });
</script>