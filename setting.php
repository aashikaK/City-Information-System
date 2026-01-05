<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_POST['service_id'] ?? $_GET['service_id'] ?? null;
$username    = $_SESSION['login'];

// fetching user's id using username session
$stmt=$pdo->prepare("SELECT id from users where username=?");
$stmt->execute([$username]);
$user=$stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("User not found. Please login again.");
}

$user_id = $user['id'];

// Get service data
$stmt = $pdo->prepare("SELECT * FROM city_services WHERE id = ?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Invalid service selected");
}

// Check capacity
if ($service['current_bookings'] >= $service['capacity']) {
    echo "<script>alert('This service is already full!'); window.history.back();</script>";
    exit;
}

$price = $service['booking_price'] ?? 0;

// Redirect to payment
header("Location: esewa_payment.php?service_id=$service_id&amount=$price");
exit;
?>
