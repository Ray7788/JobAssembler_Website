<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <style>
        html, body {
            height: 100%;
            padding: 0;
            margin: 0;
        }
        #map {
            /* configure the size of the map */
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    // initialize Leaflet
    let map = L.map('map').setView({lat: 53.4808, lng: -2.2426}, 13);
    let selectedMarker = L.marker({lat: 0, lng: 0});

    // add the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);

    // show the scale bar on the lower left corner
    L.control.scale({imperial: true, metric: true}).addTo(map);

    // show a marker on the map
    //L.marker({lon: 0, lat: 0}).bindPopup('The center of the world').addTo(map);

    map.on("click", function(e) {
        let coords = map.mouseEventToLatLng(e.originalEvent);
        selectedMarker.remove();
        selectedMarker.setLatLng({lat: coords.lat, lng: coords.lng}).addTo(map);
        console.log(coords.lat + ", " + coords.lng);
    })
</script>
</body>
</html>
