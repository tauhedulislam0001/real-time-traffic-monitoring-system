<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Real-Time Traffic Monitoring</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    <style>
        #map {
            height: 600px;
            width: 100%;
        }

        .map-controls {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .map-controls button {
            display: block;
            margin: 5px 0;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <div class="map-controls">
        <button onclick="initMap('openstreetmap')">Use OpenStreetMap</button>
        <button onclick="initMap('googlemaps')">Use Google Maps</button>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script src="https://unpkg.com/leaflet.gridlayer.googlemutant@latest/dist/Leaflet.GoogleMutant.js"></script>
    <script>
        let map;
        let trafficLayer;

        // Initialize the map
        function initMap(provider) {
            if (map) map.remove(); // Remove existing map

            if (provider === "openstreetmap") {
                map = L.map("map").setView([23.7465, 90.3840], 13); // Moghbazar, Dhaka, Bangladesh
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: '© OpenStreetMap contributors',
                }).addTo(map);
            } else if (provider === "googlemaps") {
                map = L.map("map").setView([23.7465, 90.3840], 13); // Moghbazar, Dhaka, Bangladesh
                L.gridLayer.googleMutant({
                    type: "roadmap", // or "satellite", "hybrid"
                }).addTo(map);
            }

            // Add a layer group for traffic updates
            trafficLayer = L.layerGroup().addTo(map);
        }

        // Initialize with OpenStreetMap by default
        initMap("openstreetmap");

        // Socket.IO for real-time updates
        const socket = io("http://real-time-traffic.xyz:6001");

        socket.on("connect", () => {
            console.log("Connected to Socket.IO server");
        });

        socket.on("connect_error", (error) => {
            console.error("Socket.IO connection error:", error);
        });

        socket.on("disconnect", () => {
            console.log("Disconnected from Socket.IO server");
        });

        // Handle traffic updates
        socket.on("traffic-update", (data) => {
            console.log("Traffic update:", data);

            // Clear previous traffic markers
            trafficLayer.clearLayers();

            // Add new traffic markers
            data.incidents.forEach(incident => {
                const [lat, lng] = incident.location.split(',').map(Number);
                const marker = L.marker([lat, lng]).addTo(trafficLayer);
                marker.bindPopup(`<b>${incident.type}</b><br>${incident.location}`).openPopup();
            });
        });
    </script>
</body>

</html>
