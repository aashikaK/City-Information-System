<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Information System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>

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

/* Optional Events Section */
.events {
    width:90%;
    margin:30px auto;
}
.events h2 { text-align:center; margin-bottom:20px; }
.event-card {
    background:white;
    padding:15px;
    margin-bottom:15px;
    border-radius:10px;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
    transition:0.3s;
}
.event-card:hover {
    transform: translateY(-5px);
    box-shadow:0 6px 12px rgba(0,0,0,0.2);
}

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
<div class="hero">
    <h1>Welcome to the City Information System</h1>
    <p>Complete information about cities, facilities, events, and tourism</p>
</div>

<!-- Featured Cities -->
<div class="cities">
    <div class="city-card" onclick="location.href='ktm.php'">
        <img src="images/ktm.jpg" alt="Kathmandu">
        <h3>Kathmandu Valley</h3>
        <p>Population: 1.5M | Area: 50 km²</p>
    </div>
    <div class="city-card" onclick="location.href='lalitpur.php'">
        <img src="images/lalitpur.jpg" alt="Lalitpur">
        <h3>Lalitpur</h3>
        <p>Population: 0.3M | Area: 36 km²</p>
    </div>
    <div class="city-card" onclick="location.href='bhaktapur.php'">
        <img src="images/bkt.jpg" alt="Bhaktapur">
        <h3>Bhaktapur</h3>
        <p>Population: 0.25M | Area: 15 km²</p>
    </div>
    <div class="city-card" onclick="location.href='pokhara.php'">
        <img src="images/pokhara.jpg" alt="Pokhara">
        <h3>Pokhara</h3>
        <p>Population: 0.42M | Area: 55 km²</p>
    </div>
    <div class="city-card" onclick="location.href='lumbini.php'">
        <img src="images/lumbini.jpg" alt="Lumbini">
        <h3>Lumbini</h3>
        <p>Birthplace of Lord Buddha</p>
    </div>
    <div class="city-card" onclick="location.href='chitwan.php'">
        <img src="images/chitwan.jpg" alt="Chitwan">
        <h3>Chitwan</h3>
        <p>Famous for National Park & Safari</p>
    </div>
    <div class="city-card" onclick="location.href='mustang.php'">
        <img src="images/mustang.jpg" alt="Mustang">
        <h3>Mustang</h3>
        <p>Historical Lo Manthang City</p>
    </div>
</div>

<!-- City Info Table -->
<h2 style="text-align:center; margin-top:30px;">City Highlights</h2>
<table class="info-table">
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

<!-- Optional Events Section -->
<div class="events">
    <h2>Upcoming City Events</h2>
    <div class="event-card">
        <strong>Kathmandu Street Festival</strong> - 12th Oct 2025
    </div>
    <div class="event-card">
        <strong>Pokhara Adventure Marathon</strong> - 25th Oct 2025
    </div>
    <div class="event-card">
        <strong>Lumbini Meditation Camp</strong> - 5th Nov 2025
    </div>
</div>

<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

</body>
</html>
