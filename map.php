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
h2 { text-align:center; margin:20px; color:#333; }
#filter-box { text-align:center; margin-bottom:15px; }
select { padding:6px 10px; border-radius:5px; margin:0 5px; }
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

<!-- Filter section -->
<div id="filter-box">
  <form method="GET">
    <select name="city">
      <option value="">All Cities</option>
      <option value="Kathmandu">Kathmandu</option>
      <option value="Lalitpur">Lalitpur</option>
      <option value="Bhaktapur">Bhaktapur</option>
      <option value="Pokhara">Pokhara</option>
      <option value="Lumbini">Lumbini</option>
      <option value="Chitwan">Chitwan</option>
      <option value="Mustang">Mustang</option>
    </select>

    <select name="category">
      <option value="">All Categories</option>
      <option value="Hospital">Hospital</option>
      <option value="School">School</option>
      <option value="Temple">Temple</option>
      <option value="Attraction">Attraction</option>
    </select>

    <button type="submit">Filter</button>
  </form>
</div>

<div id="map"></div>

<?php
require "db.php";

$filter_city = isset($_GET['city']) ? $_GET['city'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';

$places = [];

// TOURISM
$sql_tourism = "SELECT place_name AS name, category, city, description, image, contact_info 
                FROM tourism WHERE status = 1";
$params = [];
if ($filter_city != '') {
    $sql_tourism .= " AND city = ?";
    $params[] = $filter_city;
}
if ($filter_category != '') {
    $sql_tourism .= " AND category = ?";
    $params[] = $filter_category;
}
$stmt = $pdo->prepare($sql_tourism);
$stmt->execute($params);
$tourism = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CITY SERVICES
$sql_services = "SELECT name, category, city, description, image, icon, contact_info 
                 FROM city_services WHERE status = 1";
$params = [];
if ($filter_city != '') {
    $sql_services .= " AND city = ?";
    $params[] = $filter_city;
}
if ($filter_category != '') {
    $sql_services .= " AND category = ?";
    $params[] = $filter_category;
}
$stmt = $pdo->prepare($sql_services);
$stmt->execute($params);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$places = array_merge($tourism, $services);
?>

<!--Leaflet JS-->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var dbPlaces = <?php echo json_encode($places); ?>;

// Initialize map centered on Nepal
var map = L.map('map').setView([28.3949, 84.1240], 7);

// Load map tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Major cities
var cities = [
  { name: "Kathmandu", lat: 27.7172, lon: 85.3240, info: "Capital of Nepal" },
  { name: "Lalitpur", lat: 27.6644, lon: 85.3188, info: "Famous for Patan Durbar Square" },
  { name: "Bhaktapur", lat: 27.6710, lon: 85.4298, info: "Known for art & culture" },
  { name: "Pokhara", lat: 28.2096, lon: 83.9856, info: "Tourist hub & Phewa Lake" },
  { name: "Lumbini", lat: 27.4844, lon: 83.2760, info: "Birthplace of Lord Buddha" },
  { name: "Chitwan", lat: 27.5291, lon: 84.3542, info: "National Park & Safari" },
  { name: "Mustang", lat: 29.1833, lon: 83.8333, info: "Lo Manthang - Walled City" }
];

// Add city markers
cities.forEach(city => {
  L.marker([city.lat, city.lon]).addTo(map)
    .bindPopup("<b>" + city.name + "</b><br>" + city.info);
});


var icons = {
  "Hospital": L.icon({ iconUrl: "images/icons/hospital.png", iconSize: [30, 30] }),
  "School": L.icon({ iconUrl: "images/icons/school.png", iconSize: [30, 30] }),
  "University": L.icon({ iconUrl: "images/icons/university.png", iconSize: [30, 30] }),
  "College": L.icon({ iconUrl: "images/icons/college.png", iconSize: [30, 30] }),
  "Transport": L.icon({ iconUrl: "images/icons/transport.png", iconSize: [30, 30] }),
  "College": L.icon({ iconUrl: "images/icons/college.png", iconSize: [30, 30] }),
  "Temple": L.icon({ iconUrl: "images/icons/temple.png", iconSize: [30, 30] }),
  "Attraction": L.icon({ iconUrl: "images/icons/attraction.png", iconSize: [30, 30] }),
  "default": L.icon({ iconUrl: "images/icons/default.png", iconSize: [30, 30] })
};

// Add database places
dbPlaces.forEach(place => {
  var icon = icons[place.category] || icons["default"];
  var popup = `
    <b>${place.name}</b><br>
    Category: ${place.category}<br>
    City: ${place.city}<br>
    ${place.description ? place.description + "<br>" : ""}
    ${place.contact_info ? "📞 " + place.contact_info + "<br>" : ""}
    ${place.image ? "<img src='" + place.image + "' width='120px'><br>" : ""}
  `;

  // Default coordinates based on city
  var coords = cities.find(c => c.name.toLowerCase() === place.city.toLowerCase());
  if (coords) {
    L.marker([coords.lat, coords.lon], { icon: icon })
      .addTo(map)
      .bindPopup(popup);
  }
});
</script>
</body>
</html>
