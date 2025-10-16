<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Container */
.dashboard-container { max-width:1200px; margin:20px auto; padding:0 20px; }

/* Greeting */
.greeting { font-size:1.5rem; margin-bottom:20px; }

/* Cards */
.cards { display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:20px; }
.card {
    background:white; 
    border-radius:12px; 
    padding:20px; 
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
    text-align:center;
}
.card:hover { transform: translateY(-5px); box-shadow:0 10px 20px rgba(0,0,0,0.15); }
.card i { font-size:2rem; margin-bottom:10px; color:#4a90e2; }
.card a { display:block; margin-top:10px; color:#4a90e2; text-decoration:none; font-weight:500; }
</style>
</head>
<body>

<?php include "navbar.php"; // include your existing navbar ?>

<div class="dashboard-container">
    <div class="greeting">
        Welcome back, <strong><?php echo htmlentities($_SESSION['login']); ?></strong>!
    </div>

    <div class="cards">
        <div class="card">
            <i class="fas fa-user"></i>
            My Profile
            <a href="profile.php">Go to Profile</a>
        </div>

        <div class="card">
            <i class="fas fa-calendar-alt"></i>
            Event History
            <a href="eventhistory.php">View Events</a>
        </div>

        <div class="card">
            <i class="fas fa-ticket-alt"></i>
            Booking History
            <a href="tour-history.php">View Bookings</a>
        </div>

        <div class="card">
            <i class="fas fa-envelope-open-text"></i>
            Issue Tickets
            <a href="issuetickets.php">Submit Ticket</a>
        </div>

        <div class="card">
            <i class="fas fa-comments"></i>
            Chat Group
            <a href="chatgroup_history.php">Open Chat</a>
        </div>

        <div class="card">
            <i class="fas fa-sign-out-alt"></i>
            Logout
            <a href="logout.php">Sign Out</a>
        </div>
    </div>
</div>

</body>
</html>
