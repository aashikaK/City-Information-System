<?php
require "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<!-- AOS Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }


/* Hero Section */
.hero {
    background: url('images/city-hero.jpg') center/cover no-repeat;
    height: 50vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    color:white;
    text-align:center;
}
.hero h1 {
    font-size:3rem;
    background-color: rgba(0,0,0,0.5);
    padding:20px;
    border-radius:10px;
}
.hero p {
    font-size:1.2rem;
    margin-top:10px;
    background-color: rgba(0,0,0,0.4);
    padding: 10px 20px;
    border-radius:5px;
}

/* About Section */
.about-section {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    align-items:center;
    gap:40px;
    width:90%;
    margin:50px auto;
}
.about-image img {
    width:100%;
    max-width:450px;
    border-radius:15px;
    box-shadow:0 6px 12px rgba(0,0,0,0.15);
}
.about-text {
    max-width:600px;
}
.about-text h2 { font-size:2rem; margin-bottom:15px; color:#333; }
.about-text p { font-size:1.1rem; margin-bottom:20px; color:#555; line-height:1.6; }

/* Team Section */
.team-section {
    width:90%;
    margin:60px auto;
    text-align:center;
}
.team-section h2 {
    font-size:2.2rem;
    color:#333;
    margin-bottom:30px;
}
.team-members {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
    gap:30px;
}
.member-card {
    background:rgba(200, 218, 233, 1);
    border-radius:15px;
    padding:20px; 
    box-shadow:0 4px 8px rgba(0, 0, 0, 0.42)
    transition:0.3s;
}
.member-card:hover {
    transform: translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,0.25);
}
.member-card img {
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:15px;
}
.member-card h3 { font-size:1.3rem; color:#222; margin-bottom:5px; }
.member-card p { color:#555; font-size:1rem; }

/* Floating Icons */
.floating-icon {
    position:absolute;
    font-size:2rem;
    color:rgba(255,200,50,0.8);
    animation: float 6s ease-in-out infinite;
    z-index:0;
}
@keyframes float {
    0% { transform: translateY(0px) rotate(0deg);}
    50% { transform: translateY(-15px) rotate(20deg);}
    100% { transform: translateY(0px) rotate(0deg);}
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
@media(max-width:768px){
    .about-section { flex-direction:column; align-items:center; }
}
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Hero -->
<div class="hero" data-aos="fade-down">
    <h1>About City Information System</h1>
    <p>Connecting you with the best of cities, events, and tourism in Nepal</p>
</div>

<!-- About Section -->
<div class="about-section">
    <div class="about-image" data-aos="fade-right">
        <img src="images/city-info.jpg" alt="City Information">
    </div>
    <div class="about-text" data-aos="fade-left">
        <h2>Our Mission</h2>
        <p>City Information System is designed to provide residents and tourists with complete information about cities, landmarks, events, and tourism activities, helping them explore Nepal in a smart way.</p>
        <h2>Our Vision</h2>
        <p>We envision a connected and informed community where every citizen and traveler has easy access to accurate city information, events, and cultural insights.</p>
    </div>
</div>

<!-- Team Section -->
<div class="team-section" data-aos="fade-up">
    <h2>Meet Our Team</h2>
    <div class="team-members">
        <div class="member-card" data-aos="zoom-in">
            <img src="images/team1.jpg" alt="Team Member 1">
            <h3>Aashika Khatiwada</h3>
            <p>Project Lead & Frontend Developer</p>
        </div>
        <div class="member-card" data-aos="zoom-in" data-aos-delay="100">
            <img src="images/team2.jpg" alt="Team Member 2">
            <h3>Aagaman Sharma</h3>
            <p>Backend Developer</p>
        </div>
        <div class="member-card" data-aos="zoom-in" data-aos-delay="200">
            <img src="images/team3.jpg" alt="Team Member 3">
            <h3>Aashreet Gautam</h3>
            <p>UI/UX Designer</p>
        </div>
    </div>
</div>

<!-- Floating Icons -->
<i class="fas fa-city floating-icon" style="top:100px; left:30px;"></i>
<i class="fas fa-landmark floating-icon" style="top:200px; right:40px;"></i>
<i class="fas fa-map-marker-alt floating-icon" style="top:350px; left:60px;"></i>
<i class="fas fa-monument floating-icon" style="top:500px; right:100px;"></i>

<!-- Footer -->
<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration:1000, once:true });
</script>

</body>
</html>
