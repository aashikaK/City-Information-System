<?php
session_start();

// Protect admin pages
if(!isset($_SESSION['admin']) || $_SESSION['admin'] == ''){
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - City Information System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; } /* light gray */

/* Top Header */
.top-header{
    background:#3f84b1; /* soft darker blue */
    color:white;
    font-size:14px;
    padding:6px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.top-header ul{ list-style:none; display:flex; gap:15px; align-items:center; }
.top-header a{ color:white; text-decoration:none; }
.top-header a:hover{ color:#ffe082; } /* soft yellow accent */

/* Navbar */
.navbar{
    background:linear-gradient(90deg,#4a90e2,#6bb9f0); /* soft blue gradient */
    padding:10px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.navbar .logo a{
    color:white;
    font-size:22px;
    font-weight:bold;
    text-decoration:none;
}
.navbar ul{
    list-style:none;
    display:flex;
    gap:15px;
}
.navbar ul li a{
    color:white;
    text-decoration:none;
    font-weight:500;
}
.navbar ul li a:hover{
    color:#ffe082; /* same soft yellow hover */
}
.menu-toggle{ display:none; font-size:26px; cursor:pointer; color:white; }

/* Responsive */
@media(max-width:768px){
    .navbar ul{
        display:none;
        flex-direction:column;
        background:#6bb9f0; /* lighter blue for mobile menu */
        position:absolute;
        top:60px;
        left:0;
        width:100%;
    }
    .navbar ul.show{ display:flex; }
    .navbar ul li{ text-align:center; padding:10px 0; }
    .menu-toggle{ display:block; }
}
</style>
</head>
<body>

<!-- Top Header -->
<div class="top-header">
    <ul>
        <li><a href="/CIS/index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="/CIS/adminpanel.php"><i class="fas fa-user-shield"></i> Admin Panel</a></li>
    </ul>
    <ul>
        <li>Welcome Admin:</li>
        <li><strong><?php echo htmlentities($_SESSION['admin']); ?></strong></li>
        <li><a href="/CIS/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="/CIS/adminpanel.php">Admin Panel</a>
    </div>

    <span class="menu-toggle" onclick="toggleMenu()">☰</span>

    <ul id="menu">
        <li><a href="adminpanel.php">Dashboard</a></li>
        <li><a href="manage-users.php">Users</a></li>
        <li><a href="manage-events.php">Events</a></li>
        <li><a href="manage-tourism.php">Tourism</a></li>
        <li><a href="manage-services.php">Services</a></li>
        <li><a href="admin-issues.php">Issue Tickets</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<script>
function toggleMenu(){
    document.getElementById('menu').classList.toggle('show');
}
</script>

</body>
</html>
