<?php
require "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Information System</title>


<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
  
<!-- AOS Library CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Hero Section */
.hero {
    background: url('images/nepal-hero.jpg') center/cover no-repeat;
    height: 50vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    color:white;
    text-align:center;
}
.hero h1 {
    font-size: 3rem;
    background-color: rgba(0,0,0,0.5);
    padding: 20px;
    border-radius:10px;
}
.hero p {
    font-size: 1.2rem;
    margin-top:10px;
    background-color: rgba(0,0,0,0.4);
    padding: 10px 20px;
    border-radius:5px;
}

/* Featured Cities */
.cities {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    margin: 30px 20px;
    gap: 50px;
}
.city-card {
    background:white;
    border-radius:10px;
    box-shadow:0 6px 12px rgba(0,0,0,0.15);
    width:400px;
    text-align:center;
    transition:0.3s;
    cursor:pointer;
}
.city-card:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow:0 10px 20px rgba(0,0,0,0.25);
}
.city-card img {
    width:100%;
    height:250px;
    object-fit:cover;
    border-top-left-radius:10px;
    border-top-right-radius:10px;
}
.city-card h3 {
    padding: 15px 0 5px;
    font-size:1.4rem;
    color:#333;
}
.city-card p {
    color:#666;
    font-size:1rem;
    margin-bottom:15px;
}

/* City Info Table */
.info-table {
    width:90%;
    margin:30px auto;
    border-collapse:collapse;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
}
.info-table th, .info-table td {
    border:1px solid #ddd;
    padding:12px;
    text-align:center;
}
.info-table th {
    background:linear-gradient(90deg,#6bb9f0,#4a90e2);
    color:white;
}
.info-table tr:nth-child(even){background:#f9f9f9;}
.info-table tr:hover{background:#e0f0ff;}

/* Events Section */
.events {
    width:90%;
    margin:30px auto;
}
.events h2 { 
    text-align:center; 
    margin-bottom:20px; 
    font-size: 1.8rem; 
    color:#333; 
}
.event-card {
    background:rgba(200, 218, 233, 1);
    padding:15px;
    margin-bottom:15px;
    border-radius:10px;
    box-shadow:0 4px 8px rgba(0, 0, 0, 0.42);
    transition:0.3s;
}
.event-card:hover {
    transform: translateY(-5px);
    box-shadow:0 6px 12px rgba(0,0,0,0.2);
}
.event-card strong { font-size:1.2rem; color:#222; }
.event-date { color:#555; font-size:0.95rem; }
.event-location { font-style:italic; color:#777; font-size:0.9rem; }


/* Footer */
.footer {
    background:#3F84B1;
    color:white;
    text-align:center;
    padding:15px 20px;
    margin-top:30px;
}

/* Responsive */
@media(max-width:1024px){
    .city-card { width:45%; }
}
@media(max-width:768px){
    .cities { flex-direction:column; align-items:center; }
    .city-card { width:80%; }
}
</style>
</head>
<body>

<?php include('navbar.php'); ?> <!-- Reuse navbar -->

<!-- Hero Section -->
<div class="hero" data-aos="fade-down">
    <h1>Welcome to the City Information System</h1>
    <p>Complete information about cities, facilities, events, and tourism</p>
</div>

<!-- Featured Cities -->
<div class="cities">
    <div class="city-card" data-aos="zoom-in" data-aos-delay="0" onclick="location.href='ktm.php'">
        <img src="images/ktm.jpg" alt="Kathmandu">
        <h3>Kathmandu Valley</h3>
        <p>Population: 1.5M | Area: 50 km²</p>
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="100" onclick="location.href='lalitpur.php'">
        <img src="images/lalitpur.jpg" alt="Lalitpur">
        <h3>Lalitpur</h3>
        <p>Population: 0.3M | Area: 36 km²</p>
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="200" onclick="location.href='bhaktapur.php'">
        <img src="images/bkt.jpg" alt="Bhaktapur">
        <h3>Bhaktapur</h3>
        <p>Population: 0.25M | Area: 15 km²</p>
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="300" onclick="location.href='pokhara.php'">
        <img src="images/pokhara.jpg" alt="Pokhara">
        <h3>Pokhara</h3>
        <p>Population: 0.42M | Area: 55 km²</p>
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="400" onclick="location.href='lumbini.php'">
        <img src="images/lumbini.jpg" alt="Lumbini">
        <h3>Lumbini</h3>
        <p>Birthplace of Lord Buddha</p>
        <p>Population: 5.12M | Area: 19,707 km²</p> 
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="500" onclick="location.href='chitwan.php'">
        <img src="images/chitwan.jpg" alt="Chitwan">
        <h3>Chitwan</h3>
        <p>Famous for National Park & Safari</p>
        <p>Population: 719.9K | Area: 2,238.39 km²</p>
    </div>
    <div class="city-card" data-aos="zoom-in" data-aos-delay="600" onclick="location.href='mustang.php'">
        <img src="images/mustang.jpg" alt="Mustang">
        <h3>Mustang</h3>
        <p>Historical Lo Manthang City</p>
        <p>Population: 14.5K | Area: 3,573 km²</p>
    </div>
</div>

<!-- City Info Table -->
<h2 style="text-align:center; margin-top:30px;" data-aos="fade-up">City Highlights</h2>
<table class="info-table" data-aos="fade-up" data-aos-delay="100">
    <thead>
        <tr>
            <th>City</th>
            <th>Place</th>
            <th>Category</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Kathmandu</td>
            <td>Pashupatinath Temple</td>
            <td>Religious</td>
            <td>Famous Hindu temple by Bagmati River</td>
        </tr>
        <tr>
            <td>Lalitpur</td>
            <td>Patan Durbar Square</td>
            <td>Historical</td>
            <td>Ancient square with temples and museums</td>
        </tr>
        <tr>
            <td>Bhaktapur</td>
            <td>Bhaktapur Durbar Square</td>
            <td>Historical</td>
            <td>Famous for its art, architecture, and culture</td>
        </tr>
        <tr>
            <td>Pokhara</td>
            <td>Phewa Lake</td>
            <td>Nature</td>
            <td>Beautiful lake with boating and Annapurna view</td>
        </tr>
        <tr>
            <td>Lumbini</td>
            <td>Maya Devi Temple</td>
            <td>Religious</td>
            <td>Birthplace of Lord Buddha</td>
        </tr>
        <tr>
            <td>Chitwan</td>
            <td>Chitwan National Park</td>
            <td>Wildlife</td>
            <td>Safari and wildlife exploration</td>
        </tr>
        <tr>
            <td>Mustang</td>
            <td>Lo Manthang</td>
            <td>Historical</td>
            <td>Ancient walled city in Mustang</td>
        </tr>
    </tbody>
</table>

<!-- Upcoming Events Section -->
<div class="events" data-aos="fade-up" data-aos-delay="200">
    <h2>Upcoming Popular Events</h2>
    <?php
    try {
        // fetch events where event_date >= today AND is_popular=1
        $stmt = $pdo->prepare("SELECT event_name, city, description, event_date, location 
                               FROM events 
                               WHERE event_date >= CURDATE() AND is_popular=1
                               ORDER BY event_date ASC 
                               LIMIT 5");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($events) {
            foreach ($events as $event) {
                echo "<div class='event-card'>";
                echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong>";
                echo " <span class='event-date'>(" . date("d M Y", strtotime($event['event_date'])) . ")</span>";
                echo "<div class='event-location'>📍 " . htmlspecialchars($event['city']) . " - " . htmlspecialchars($event['location']) . "</div>";
                echo "<p>" . htmlspecialchars($event['description']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; color:#777;'>No upcoming popular events found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red; text-align:center;'>Error fetching events: " . $e->getMessage() . "</p>";
    }
    ?>
</div>

<div class="footer" data-aos="fade-up" data-aos-delay="300">
    &copy; 2025 City Information System. All rights reserved.
</div>


<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration:1000, once:true });
</script>

</body>
</html>
