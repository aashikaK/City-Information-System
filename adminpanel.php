<?php
 include "admin-navbar.php"; 

// Protect admin pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

require "db.php";

// COUNT values using PDO
$usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$adminsCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();

$eventsCount = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$popularEventsCount = $pdo->query("SELECT COUNT(*) FROM events WHERE is_popular = 1")->fetchColumn();

$bookingsCount = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$pendingBookingsCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='pending'")->fetchColumn();

$tourismCount = $pdo->query("SELECT COUNT(*) FROM tourism")->fetchColumn();
$servicesCount = $pdo->query("SELECT COUNT(*) FROM city_services")->fetchColumn();

$writeUsCount = $pdo->query("SELECT COUNT(*) FROM write_us WHERE status='new'")->fetchColumn();



// $issuesCount = $pdo->query("SELECT COUNT(*) FROM issue_tickets")->fetchColumn();
// $pendingIssuesCount = $pdo->query("SELECT COUNT(*) FROM issue_tickets WHERE status='pending'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - CIS</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Cards Container */
.dashboard-container { max-width:1200px; margin:20px auto; padding:0 20px; }
.greeting { font-size:1.5rem; margin-bottom:20px; }

/* Cards Grid */
.cards { display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:30px; }
.card {
    background:white; 
    border-radius:12px; 
    padding:25px; 
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
    text-align:center;
}
.card:hover { transform: translateY(-5px); box-shadow:0 10px 20px rgba(0,0,0,0.15); }
.card i { font-size:2.5rem; margin-bottom:15px; color:#4a90e2; }
.card h2 { font-size:1.8rem; margin-bottom:10px; }
.card a { display:block; margin-top:10px; color:#4a90e2; text-decoration:none; font-weight:500; }
.card a:hover { text-decoration:underline; }

/* Navbar from your admin style */
/* ---------- RESPONSIVE DESIGN ---------- */

/* Tablets (≤ 992px) */
@media (max-width: 992px) {
    .dashboard-container {
        padding: 0 15px;
    }

    .greeting {
        font-size: 1.3rem;
        text-align: center;
    }

    .card {
        padding: 20px;
    }

    .card h2 {
        font-size: 1.6rem;
    }
}

/* Mobile (≤ 600px) */
@media (max-width: 600px) {
    .dashboard-container {
        margin: 15px auto;
    }

    .greeting {
        font-size: 1.2rem;
        text-align: center;
    }

    .cards {
        grid-template-columns: 1fr; /* single column */
        gap: 20px;
    }

    .card {
        padding: 18px;
    }

    .card i {
        font-size: 2.2rem;
    }

    .card h2 {
        font-size: 1.4rem;
    }

    .card a {
        font-size: 0.95rem;
    }
}
</style>
</head>
<body>



<div class="dashboard-container">
    <div class="greeting">
        Welcome back, <strong><?php echo htmlentities($_SESSION['admin']); ?></strong>!
    </div>

    <div class="cards">
        <div class="card">
            <i class="fas fa-users"></i>
            <h2><?php echo $usersCount; ?></h2>
            Users
            <a href="manage-users.php">Manage Users</a>
        </div>

        <div class="card">
            <i class="fas fa-user-shield"></i>
            <h2><?php echo $adminsCount; ?></h2>
            Admins
            <a href="manage-admins.php">Manage Admins</a>
        </div>

        <div class="card">
            <i class="fas fa-calendar-alt"></i>
            <h2><?php echo $eventsCount; ?></h2>
            Total Events
            <a href="manage-events.php">Manage Events</a>
        </div>

        <div class="card">
            <i class="fas fa-star"></i>
            <h2><?php echo $popularEventsCount; ?></h2>
            Popular Events
            <a href="popular-events.php">View Popular</a>
        </div>

        <div class="card">
            <i class="fas fa-ticket-alt"></i>
            <h2><?php echo $bookingsCount; ?></h2>
            Total Bookings
            <a href="manage-bookings.php">Manage Bookings</a>
        </div>

        <div class="card">
            <i class="fas fa-hourglass-half"></i>
            <h2><?php echo $pendingBookingsCount; ?></h2>
            Pending Bookings
            <a href="manage-bookings.php">View Pending</a>
        </div>

        <div class="card">
            <i class="fas fa-map-marker-alt"></i>
            <h2><?php echo $tourismCount; ?></h2>
            Tourism Spots
            <a href="manage-tourism.php">Manage Tourism</a>
        </div>

        <div class="card">
            <i class="fas fa-hospital"></i>
            <h2><?php echo $servicesCount; ?></h2>
            City Services
            <a href="manage-services.php">Manage Services</a>
        </div>

        <div class="card">
            <i class="fas fa-envelope-open-text"></i>
            <h2><?php echo $issuesCount; ?></h2>
            Issue Tickets
            <a href="admin-issues.php">View Tickets</a>
        </div>

        <div class="card">
            <i class="fas fa-clock"></i>
            <h2><?php echo $pendingIssuesCount; ?></h2>
            Pending Issues
            <a href="admin-issues.php">View Pending</a>
        </div>

        <div class="card">
    <i class="fas fa-envelope"></i>
    <h2><?php echo $writeUsCount; ?></h2>
    Messages
    <a href="manage-messages.php">View Messages</a>
</div>


    </div>
</div>

</body>
</html>
