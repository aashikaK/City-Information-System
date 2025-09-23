<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Map - City Information System</title>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Page title */
h2 {
  text-align:center;
  margin:20px;
  color:#333;
}

/* Map */
#map {
  height: 90vh;
  width: 90%;
  margin: 0 auto 30px;
  border-radius: 12px;
  box-shadow: 0 6px 14px rgba(0,0,0,0.25);
}
</style>
</head>
<body>

<?php include('navbar.php'); ?> 

<h2>Explore Nepal on Map</h2>

<div id="map"></div>

<!--Leaflet JS-->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Initialize map centered on Nepal
var map = L.map('map').setView([28.3949, 84.1240], 7);

// Load tiles from OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Cities array
var cities = [
  { name: "Kathmandu", lat: 27.7172, lon: 85.3240, info: "Capital of Nepal" },
  { name: "Lalitpur", lat: 27.6644, lon: 85.3188, info: "Famous for Patan Durbar Square" },
  { name: "Bhaktapur", lat: 27.6710, lon: 85.4298, info: "Known for art & culture" },
  { name: "Pokhara", lat: 28.2096, lon: 83.9856, info: "Tourist hub & Phewa Lake" },
  { name: "Lumbini", lat: 27.4844, lon: 83.2760, info: "Birthplace of Lord Buddha" },
  { name: "Chitwan", lat: 27.5291, lon: 84.3542, info: "National Park & Safari" },
  { name: "Mustang", lat: 29.1833, lon: 83.8333, info: "Lo Manthang - Walled City" }
];

//Add markers for cities
cities.forEach(city => {
  L.marker([city.lat, city.lon]).addTo(map)
    .bindPopup("<b>" + city.name + "</b><br>" + city.info);
});
</script>

</body>
</html>
