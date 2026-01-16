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
#filter-box, #radio-box { text-align:center; margin-bottom:15px; }
select { padding:6px 10px; border-radius:5px; margin:0 5px; }
#map {
  height: 80vh;
  width: 90%;
  margin: 0 auto 20px;
  border-radius: 12px;
  box-shadow: 0 6px 14px rgba(0,0,0,0.25);
}
#nearest-places {
  text-align:center;
  font-weight:bold;
  color:#333;
  margin-bottom:30px;
}
#nearest-instruction {
  text-align:center;
  font-size:16px;
  color:black;
  margin-bottom:15px;
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
      <option value="Kathmandu" <?php if(isset($_GET['city']) && $_GET['city']=="Kathmandu") echo "selected"; ?>>Kathmandu</option>
      <option value="Lalitpur" <?php if(isset($_GET['city']) && $_GET['city']=="Lalitpur") echo "selected"; ?>>Lalitpur</option>
      <option value="Bhaktapur" <?php if(isset($_GET['city']) && $_GET['city']=="Bhaktapur") echo "selected"; ?>>Bhaktapur</option>
      <option value="Pokhara" <?php if(isset($_GET['city']) && $_GET['city']=="Pokhara") echo "selected"; ?>>Pokhara</option>
      <option value="Lumbini" <?php if(isset($_GET['city']) && $_GET['city']=="Lumbini") echo "selected"; ?>>Lumbini</option>
      <option value="Chitwan" <?php if(isset($_GET['city']) && $_GET['city']=="Chitwan") echo "selected"; ?>>Chitwan</option>
      <option value="Mustang" <?php if(isset($_GET['city']) && $_GET['city']=="Mustang") echo "selected"; ?>>Mustang</option>
    </select>

    <select name="category">
      <option value="">All Categories</option>
      <option value="Hospital" <?php if(isset($_GET['category']) && $_GET['category']=="Hospital") echo "selected"; ?>>Hospital</option>
      <option value="School" <?php if(isset($_GET['category']) && $_GET['category']=="School") echo "selected"; ?>>School</option>
      <option value="University" <?php if(isset($_GET['category']) && $_GET['category']=="University") echo "selected"; ?>>University</option>
      <option value="College" <?php if(isset($_GET['category']) && $_GET['category']=="College") echo "selected"; ?>>College</option>
      <option value="Transport" <?php if(isset($_GET['category']) && $_GET['category']=="Transport") echo "selected"; ?>>Transport</option>
      <option value="Hotel" <?php if(isset($_GET['category']) && $_GET['category']=="Hotel") echo "selected"; ?>>Hotel</option>
      <option value="Government" <?php if(isset($_GET['category']) && $_GET['category']=="Government") echo "selected"; ?>>Government</option>
      <option value="Bank" <?php if(isset($_GET['category']) && $_GET['category']=="Bank") echo "selected"; ?>>Bank</option>
      <option value="Firestation" <?php if(isset($_GET['category']) && $_GET['category']=="Firestation") echo "selected"; ?>>Firestation</option>
      <option value="Temple" <?php if(isset($_GET['category']) && $_GET['category']=="Temple") echo "selected"; ?>>Temple</option>
      <option value="Tourism" <?php if(isset($_GET['category']) && $_GET['category']=="Tourism") echo "selected"; ?>>Tourism</option>
    </select>

    <button type="submit">Filter</button>
  </form>
</div>

<!-- Instruction for radio buttons -->
<div id="nearest-instruction">
  Choose a category below to find your nearest locations: <span style="color:#555; font-size:14px;">( Please wait few seconds until it loads....) </span>
</div>

<!-- Radio buttons for nearest places -->
<div id="radio-box">
  <?php
    $categories = ["Hospital","School","University","College","Transport","Hotel","Government","Bank","Firestation","Temple","Tourism"];
    foreach($categories as $cat){
        echo '<label style="margin-right:10px;">
                <input type="radio" name="nearest-category" value="'.htmlspecialchars($cat).'"> '.htmlspecialchars($cat).'
              </label>';
    }
  ?>
</div>

<!-- Nearest places -->
<div id="nearest-places">
  <!-- Red pin image + names will appear here -->
</div>

<div id="map"></div>

<?php
require "db.php";

$filter_city = isset($_GET['city']) ? $_GET['city'] : '';
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';

$places = [];

// Tourism
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

// City services
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

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var dbPlaces = <?php echo json_encode($places); ?>;

// Major cities with coordinates
var cities = [
  { name: "Kathmandu", lat: 27.7172, lon: 85.3240, info: "Capital of Nepal" },
  { name: "Lalitpur", lat: 27.6644, lon: 85.3188, info: "Famous for Patan Durbar Square" },
  { name: "Bhaktapur", lat: 27.6710, lon: 85.4298, info: "Known for art & culture" },
  { name: "Pokhara", lat: 28.2096, lon: 83.9856, info: "Tourist hub & Phewa Lake" },
  { name: "Lumbini", lat: 27.4844, lon: 83.2760, info: "Birthplace of Lord Buddha" },
  { name: "Chitwan", lat: 27.5291, lon: 84.3542, info: "National Park & Safari" },
  { name: "Mustang", lat: 29.1833, lon: 83.8333, info: "Lo Manthang - Walled City" }
];

// Default map center
var defaultLat = 28.3949;
var defaultLon = 84.1240;
var defaultZoom = 7;

// Zoom to filtered city if any
var cityFilter = "<?php echo $filter_city; ?>";
if(cityFilter){
    var cityObj = cities.find(c => c.name.toLowerCase() === cityFilter.toLowerCase());
    if(cityObj){
        defaultLat = cityObj.lat;
        defaultLon = cityObj.lon;
        defaultZoom = 12;
    }
}

var map = L.map('map').setView([defaultLat, defaultLon], defaultZoom);

// Tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Add city markers
cities.forEach(city => {
  L.marker([city.lat, city.lon])
    .addTo(map)
    .bindPopup("<b>" + city.name + "</b><br>" + city.info);
});

// Icons for categories
var icons = {
  "Hospital": L.icon({ iconUrl: "images/icons/hospital.png", iconSize: [20, 20] }),
  "School": L.icon({ iconUrl: "images/icons/school.png", iconSize: [20, 20] }),
  "University": L.icon({ iconUrl: "images/icons/university.png", iconSize: [20, 20] }),
  "College": L.icon({ iconUrl: "images/icons/college.png", iconSize: [20, 20] }),
  "Transport": L.icon({ iconUrl: "images/icons/transport.png", iconSize: [20, 20] }),
  "Hotel": L.icon({ iconUrl: "images/icons/hotel.png", iconSize: [20, 20] }),
  "Government": L.icon({ iconUrl: "images/icons/government.png", iconSize: [20, 20] }),
  "Bank": L.icon({ iconUrl: "images/icons/bank.png", iconSize: [20, 20] }),
  "Firestation": L.icon({ iconUrl: "images/icons/firestation.png", iconSize: [20, 20] }),
  "Temple": L.icon({ iconUrl: "images/icons/temple.png", iconSize: [20, 20] }),
  "Tourism": L.icon({ iconUrl: "images/icons/tourism.png", iconSize: [20, 20] }),
  "default": L.icon({ iconUrl: "images/icons/default.png", iconSize: [20, 20] })
};

// Add database places with small icons
dbPlaces.forEach(place => {
  var icon = icons[place.category] || icons["default"];
  var coords = cities.find(c => c.name.toLowerCase() === place.city.toLowerCase());
  if (coords) {
    var offsetLat = (Math.random()-0.5)*0.04;
    var offsetLon = (Math.random()-0.5)*0.04;
    var lat = coords.lat + offsetLat;
    var lon = coords.lon + offsetLon;

    var popup = `<b>${place.name}</b><br>
                 Category: ${place.category}<br>
                 City: ${place.city}<br>
                 ${place.description ? place.description + "<br>" : ""}
                 ${place.contact_info ? "📞 " + place.contact_info + "<br>" : ""}
                 ${place.image ? "<img src='" + place.image + "' width='120px'><br>" : ""}`;
    
    L.marker([lat, lon], {icon: icon}).addTo(map).bindPopup(popup);
  }
});

// Highlight icon for nearest places
var highlightIcon = L.icon({
  iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
  iconSize: [40,40],
  iconAnchor: [20,40],
  popupAnchor: [0,-35]
});

// Haversine distance function
function getDistance(lat1, lon1, lat2, lon2){
    const R = 6371;
    const dLat = (lat2-lat1)*Math.PI/180;
    const dLon = (lon2-lon1)*Math.PI/180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
    const c = 2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
    return R * c;
}

// Radio button nearest logic
document.querySelectorAll('input[name="nearest-category"]').forEach(function(radio){
    radio.addEventListener('change', function(){
        var category = radio.value;
        var nearestDiv = document.getElementById("nearest-places");
        nearestDiv.innerHTML = "";

        if (!navigator.geolocation) return alert("Geolocation not supported");

        navigator.geolocation.getCurrentPosition(function(position){
            var userLat = position.coords.latitude;
            var userLon = position.coords.longitude;

            // remove old red markers
            if(window.redMarkers) window.redMarkers.forEach(m=>map.removeLayer(m));
            window.redMarkers = [];

            var matchingPlaces = dbPlaces.filter(p=>p.category.toLowerCase()===category.toLowerCase());

            matchingPlaces.forEach(p=>{
                var coords = cities.find(c=>c.name.toLowerCase()===p.city.toLowerCase());
                if(coords){
                    var offsetLat = (Math.random()-0.5)*0.04;
                    var offsetLon = (Math.random()-0.5)*0.04;
                    p.lat = coords.lat + offsetLat;
                    p.lon = coords.lon + offsetLon;
                    p.distance = getDistance(userLat,userLon,p.lat,p.lon);
                }
            });

            matchingPlaces.sort((a,b)=>a.distance - b.distance);

            // Show red pin image above names
            if(matchingPlaces.length >= 2){
                nearestDiv.innerHTML = `<img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" 
                                         width="20" height="20" style="vertical-align:middle; margin-right:5px;">
                                         Nearest locations:<br>`;
            }

            // Add nearest 2 red pins on map
            matchingPlaces.slice(0,2).forEach(p=>{
                var marker = L.marker([p.lat,p.lon],{icon:highlightIcon,riseOnHover:true})
                              .addTo(map)
                              .bindPopup(`<b>${p.name}</b><br>Category: ${p.category}`);
                window.redMarkers.push(marker);
            });

            if(window.redMarkers.length>0){
                var group = new L.featureGroup(window.redMarkers);
                map.fitBounds(group.getBounds().pad(0.4));
            }

            // Show names below pin image
            matchingPlaces.slice(0,2).forEach(p=>{
                nearestDiv.innerHTML += `${p.name} (${p.city})<br>`;
            });

        }, function(){
            alert("Geolocation is required to find nearest places");
        });
    });
});
</script>

</body>
</html>
