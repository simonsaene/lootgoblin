<script>
    var grindSpots = @json($grindSpots);
    var spotCount = @json($spotCount);
    var hours = @json($hoursPerSpot);
    var silverPerSpot = @json($silverPerSpot);
    var avgSilverPerSpot = @json($avgSilverPerSpot);

    var colorScale = chroma.scale([
        "#FF1493",   // Deep Pink
        "#00BFFF",   // Deep Sky Blue
        "#32CD32",   // Lime Green
        "#8A2BE2",   // Blue Violet
        "#FFD700",   // Gold
        "#FF4500",   // Orange Red
        "#6600FF",   // Purple
        "#00FFFF",   // Cyan
        "#FF6600",   // Orange
        "#FF0000",   // Red
        "#FFCC00",   // Yellow
        "#0000FF",   // Blue
        "#FF00FF",   // Magenta
        "#FFCC00",   // Yellow
        ])
        .mode('lab')
        .colors(grindSpots.length);

    var datasetColors = grindSpots.map(function (spot, index) {
        return colorScale[index];
    });
</script>