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

<?php
require "db.php";
$places=[];
$sql_tourism="SELECT place_name AS name, category, city, description, image 
                FROM tourism";
$stmt=$pdo->prepare($sql_tourism);
$stmt->execute();
$result1=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($result1 as $r1 ){
  $places[]=[
    "name"=>$r1['name'],
    "category"=>$r1['category'],
    "city"=>$r1['city'],
    "description"=>$r1['description'],
    "image"=>$r1['image']
  ];
}
$sql_services = "SELECT name, category, city, description, image, icon 
                 FROM city_services ";
$stmt=$pdo->prepare($sql_services);
$stmt->execute();
$result2=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($result2 as $r2){
  $places[]=[
    "name"=>$r2['name'],
    "category"=>$r2['category'],
    "city"=>$r2['city'],
    "description"=>$r2['description'],
    "image"=>$r2['image'],
    "icon"=>$r2['icon']
  ];
}
?>

<!--Leaflet JS-->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Initialize map centered on Nepal

var dbPlaces = <?php echo json_encode($places); ?>;
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

// Icons for categories (you need to create these icons in an 'icons/' folder)
var icons = {
    "Hospital": L.icon({ iconUrl: "icons/hospital.png", iconSize: [30, 30] }),
    "School": L.icon({ iconUrl: "icons/school.png", iconSize: [30, 30] }),
    "Temple": L.icon({ iconUrl: "icons/temple.png", iconSize: [30, 30] }),
    "Attraction": L.icon({ iconUrl: "icons/attraction.png", iconSize: [30, 30] }),
    "default": L.icon({ iconUrl: "icons/default.png", iconSize: [30, 30] })
};

// Add markers for DB places
dbPlaces.forEach(place => {
    var icon = icons[place.category] || icons["default"];
    var popupContent = `
        <b>${place.name}</b><br>
        Category: ${place.category}<br>
        ${place.description ? place.description + "<br>" : ""}
        ${place.image ? "<img src='" + place.image + "' width='120px'><br>" : ""}
    `;
    L.marker([place.lat || 0, place.lon || 0], { icon: icon })
        .addTo(map)
        .bindPopup(popupContent);
});

</script>

</body>
</html>
