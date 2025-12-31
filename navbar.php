<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Information System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" crossorigin="anonymous" />

<style>
* { 
    margin:0; 
    padding:0; 
    box-sizing:border-box; 
    font-family:"Segoe UI", Arial, sans-serif; 
}
body { 
    background:#f4f7fb; 
}

/* ================= TOP HEADER ================= */
.top-header {
    background:#3F84B1;
    color:white;
    font-size:14px;
    padding:5px 20px;
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    align-items:center;
}
.top-header ul { 
    display:flex; 
    flex-wrap:wrap; 
    list-style:none; 
    align-items:center; 
}
.top-header ul li { 
    margin-right:15px; 
}
.top-header a { 
    text-decoration:none; 
    color:white; 
    transition:0.3s; 
}
.top-header a:hover { 
    color:#ffe082; 
}
#notification-button { 
    background:#ffcd38; 
    border:none; 
    padding:5px 8px; 
    border-radius:5px; 
    cursor:pointer; 
}

/* ================= MAIN NAVBAR ================= */
.navbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:linear-gradient(90deg,#6bb9f0,#4a90e2);
    padding:10px 20px;
    color:white;
    position:sticky;
    top:0;
    z-index:1000;
}
.navbar .logo a { 
    color:white; 
    text-decoration:none; 
    font-size:18px; 
    font-weight:bold; 
}
.navbar .logo a span { 
    color:#ffe082; 
}
.navbar ul { 
    display:flex; 
    list-style:none; 
    gap:13px; 
}
.navbar ul li a { 
    color:white; 
    text-decoration:none; 
    font-weight:490; 
    transition:0.3s; 
}
.navbar ul li a:hover { 
    color:#ffe082; 
}

/* Hamburger menu */
.menu-toggle { 
    display:none; 
    font-size:28px; 
    cursor:pointer; 
    color:white; 
}

/* ================= RESPONSIVE ================= */
@media(max-width:1000px){
    /* Mobile layout for navbar */
    .navbar ul {
        flex-direction:column;
        display:none;
        width:100%;
        background:#4a90e2;
        position:absolute;
        top:60px;
        left:0;
        padding:10px 0;
    }
    .navbar ul.show { display:flex; }
    .navbar ul li { 
        padding:12px 0;  /* slightly larger vertical spacing for mobiles */
        text-align:center; 
    }
    .menu-toggle { display:block; }

    /* Mobile spacing for top-header */
    .top-header { flex-direction:column; align-items:flex-start; }
    .top-header ul { flex-direction:column; margin-top:5px; }
    .top-header ul li { 
        margin-bottom:8px; /* vertical spacing for mobile */
    }

    /* Extra spacing between icon and text only for mobile */
    .navbar ul li a i,
    .top-header ul li a i {
        margin-right:6px;
    }
}

</style>
</head>

<body>

<!-- ================= TOP HEADER ================= -->
<div class="top-header">
    <ul>
        <!-- Home always visible -->
        <li><a href="/CIS/index.php"><i class="fas fa-home"></i> Home</a></li>

        <?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>

            <li><a href="/CIS/about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
<li><a href="/CIS/services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
<li><a href="/CIS/tourism.php"><i class="fas fa-map-marked-alt"></i> Tourism</a></li>
<li><a href="/CIS/map.php"><i class="fas fa-map"></i> Map</a></li>
 <li>
                <button id="notification-button">
                    <a href="/CIS/notification.php"><i class="fas fa-bell"></i></a>
                </button>
            </li>

        <?php } 
        // else { 
        // ?>

            <!-- Before login -->
            <!-- <li><a href="/CIS/admin_login.php"><i class="fas fa-user-shield"></i> Admin Login</a></li> -->

    </ul>

    <ul>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>

            <li>Welcome :</li>
            <li><?php echo htmlentities($_SESSION['login']); ?></li>
            <li><a href="/CIS/logout.php" style="font-size:16px;font-weight:500;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

        <?php } else { ?>

            <!-- Toll number MUST stay -->
            <li>Toll Number: 123-4568790</li>
            <!-- <li><a href="/CIS/signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
            <li><a href="/CIS/signin.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li> -->

        <?php } ?>
    </ul>
</div>

<!-- ================= MAIN NAVBAR ================= -->
<nav class="navbar">
    <div class="logo">
        <a href="/CIS/index.php">City <span>Information System</span></a>
    </div>

    <span class="menu-toggle" onclick="toggleMenu()">☰</span>

    <ul id="menu">

<?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>

        <!-- AFTER LOGIN (matches after-code logic) -->
        <li><a href="/CIS/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li><a href="/CIS/profile.php"><i class="fas fa-user"></i> My Profile</a></li>
<li><a href="/CIS/eventhistory.php"><i class="fas fa-calendar-alt"></i> Event History</a></li>
<li><a href="/CIS/tour-history.php"><i class="fas fa-ticket-alt"></i> Booking History</a></li>
<li><a href="/CIS/events.php"><i class="fas fa-calendar"></i> Events</a></li>
 <li><a href="issuetickets.php"><i class="fas fa-ticket-alt"></i> Issue Ticket</a></li>
<li><a href="/CIS/write-us.php"><i class="fas fa-pen"></i> Write Us</a></li>
<!-- <li><a href="/CIS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li> -->


<?php } else { ?>

        <!-- BEFORE LOGIN -->
        <li><a href="/CIS/index.php">Home</a></li>
        <li><a href="/CIS/about.php">About Us</a></li>
        <li><a href="/CIS/services.php">Services</a></li>
        <li><a href="/CIS/tourism.php">Tourism</a></li>
        <li><a href="/CIS/events.php">Events</a></li>
        <li><a href="/CIS/map.php">Map</a></li>
        <li><a href="/CIS/signin.php">Sign In</a></li>
        <li><a href="/CIS/signup.php">Sign Up</a></li>

<?php } ?>

    </ul>
</nav>

<script>
function toggleMenu() {
    document.getElementById('menu').classList.toggle('show');
}
</script>

</body>
</html>
