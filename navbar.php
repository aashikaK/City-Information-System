<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Information System</title>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Top Header */
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
.top-header ul { display:flex; flex-wrap:wrap; list-style:none; align-items:center; }
.top-header ul li { margin-right:15px; }
.top-header a { text-decoration:none; color:white; transition:0.3s; }
.top-header a:hover { color:#ffe082; }
#notification-button { background:#ffcd38; border:none; color:#333; padding:5px 8px; border-radius:5px; cursor:pointer; }

/* Main Navbar */
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
.navbar .logo a { color:white; text-decoration:none; font-size:24px; font-weight:bold; }
.navbar .logo a span { color:#ffe082; }
.navbar ul { display:flex; list-style:none; gap:15px; }
.navbar ul li a { color:white; text-decoration:none; font-weight:500; transition:0.3s; }
.navbar ul li a:hover { color:#ffe082; }

/* Hamburger menu */
.menu-toggle { display:none; font-size:28px; cursor:pointer; color:white; }

/* Responsive */
@media(max-width:768px){
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
    .navbar ul li { padding:10px 0; text-align:center; }
    .menu-toggle { display:block; }
    .top-header { flex-direction:column; align-items:flex-start; }
    .top-header ul { flex-direction:column; margin-top:5px; }
    .top-header ul li { margin-bottom:5px; }
}
</style>
</head>
<body>


<!-- Top Header -->
<div class="top-header">
    <ul>
        <li><a href="/CIS/index.php"><i class="fas fa-home"></i> Home</a></li>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>
            <li><a href="/CIS/profile.php"><i class="fas fa-user"></i> My Profile</a></li>
            <li><a href="/CIS/change-password.php"><i class="fas fa-key"></i> Change Password</a></li>
            <li><a href="/CIS/eventhistory.php"><i class="fas fa-calendar-alt"></i> Event History</a></li>
            <li><a href="/CIS/tour-history.php"><i class="fas fa-ticket-alt"></i> Booking History</a></li>
            <li><a href="/CIS/customereventhistory.php"><i class="fas fa-users"></i> User Details</a></li>
            <li><a href="/CIS/issuetickets.php"><i class="fas fa-envelope-open-text"></i> Issue Tickets</a></li>
            <li><a href="/CIS/chatgroup_history.php"><i class="fas fa-comments"></i> Chat Group</a></li>
            <li>
                <button id="notification-button">
                    <a href="/CIS/notification.php"><i class="fas fa-bell"></i></a>
                </button>
            </li>
        <?php } else { ?>
            <li><a href="/CIS/admin/index.php"><i class="fas fa-user-shield"></i> Admin Login</a></li>
        <?php } ?>
    </ul>

    <ul>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>
            <li>Welcome :</li>
            <li><?php echo htmlentities($_SESSION['login']); ?></li>
            <li><a href="/CIS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php } else { ?>
            <li>Toll Number: 123-4568790</li>
            <li><a href="/CIS/signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
            <li><a href="/CIS/signin.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
        <?php } ?>
    </ul>
</div>

<!-- Main Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="/CIS/index.php">City <span>Information System</span></a>
    </div>

    <span class="menu-toggle" onclick="toggleMenu()">☰</span>

    <ul id="menu">
        <li><a href="/CIS/index.php">Home</a></li>
        <li><a href="/CIS/about.php">About Us</a></li>
        <li><a href="/CIS/services.php">Services</a></li>
        <li><a href="/CIS/tourism.php">Tourism</a></li>
        <li><a href="/CIS/events.php">Events</a></li>
        <li><a href="/CIS/map.php">Map</a></li>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] != '') { ?>
            <li><a href="/CIS/write-us.php">Write Us</a></li>
            <li><a href="/CIS/logout.php">Logout</a></li>
        <?php } else { ?>
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
