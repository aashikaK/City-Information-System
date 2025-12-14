<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$service_id = $_POST['service_id'] ?? $_GET['service_id'] ?? null;
$user_id    = $_SESSION['user_id'];

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
