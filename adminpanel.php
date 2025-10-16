<?php
// Only start session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if(!isset($_SESSION['admin']) || $_SESSION['admin'] == ''){
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - CIS</title>
</head>
<body>
    <?php include "navbar.php"; ?>
    <h1>Welcome to Admin Panel</h1>
    <p>Hello, <?php echo htmlentities($_SESSION['admin']); ?>!</p>
    <p>This is a dummy page to test admin session.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
