<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genshin Impact - Interactive World Map</title>
    <link rel="stylesheet" href="map.css">
</head>
<body>

    <div class="game-border"></div>

    <div class="map-wrapper" id="mapWrapper">
        <img class="map-image" id="mapImg" src="../image/map.jpg" alt="Genshin Impact World Map">
    </div>

    <div class="controls">
        <button class="btn" id="zoomIn" title="Zoom In">+</button>
        <button class="btn" id="zoomOut" title="Zoom Out">−</button>
        <button class="btn" id="resetZoom" title="Reset Kontrol">⟲</button>
    </div>

    <script src="map.js"></script>
</body>
</html>