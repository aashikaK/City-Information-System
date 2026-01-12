<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_POST['service_id'] ?? $_GET['service_id'] ?? null;
$category   = $_POST['category'] ?? $_GET['category'] ?? null;
$username   = $_SESSION['login'];

// Fetch user ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("User not found. Please login again.");
$user_id = $user['id'];

// Fetch service data
$stmt = $pdo->prepare("SELECT * FROM city_services WHERE id=?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$service) die("Invalid service selected");

// Check capacity
$available_capacity = $service['capacity'] - $service['current_bookings'];

if ($category == "Hospital") {
    // Hospital: 1 appointment date
    $appointment_date = $_POST['appointment_date'] ?? null;
    if (!$appointment_date) {
        echo "<script>alert('Please select an appointment date'); window.history.back();</script>";
        exit;
    }

    if ($appointment_date < date('Y-m-d')) {
        echo "<script>alert('Appointment date cannot be in the past'); window.history.back();</script>";
        exit;
    }

    if ($available_capacity < 1) {
        echo "<script>alert('This hospital is fully booked!'); window.history.back();</script>";
        exit;
    }

    $days = 1;
    $total_price = $service['booking_price'];

} elseif ($category == "Hotel") {
    // Hotel: multiple rooms and days
    $start_date = $_POST['start_date'] ?? null;
    $end_date   = $_POST['end_date'] ?? null;
    $rooms      = $_POST['rooms'] ?? 1;

    if (!$start_date || !$end_date) {
        echo "<script>alert('Please select check-in and check-out dates'); window.history.back();</script>";
        exit;
    }

    if ($start_date < date('Y-m-d') || $end_date < date('Y-m-d')) {
        echo "<script>alert('Dates cannot be in the past'); window.history.back();</script>";
        exit;
    }

    $days = (strtotime($end_date) - strtotime($start_date)) / (60*60*24);
    if ($days < 1) {
        echo "<script>alert('Check-out date must be after check-in date'); window.history.back();</script>";
        exit;
    }

    if ($rooms > $available_capacity) {
        echo "<script>alert('Not enough rooms available!'); window.history.back();</script>";
        exit;
    }

    $total_price = $service['booking_price'] * $days * $rooms;

} else {
    // fallback
    $days = 1;
    $total_price = $service['booking_price'];
}

// Save booking details in session
$_SESSION['service_id']   = $service_id;
$_SESSION['category']     = $category;
$_SESSION['total_price']  = $total_price;
$_SESSION['days']         = $days;

if ($category == "Hospital") {
    $_SESSION['appointment_date'] = $appointment_date;
} elseif ($category == "Hotel") {
    $_SESSION['start_date'] = $start_date;
    $_SESSION['end_date']   = $end_date;
    $_SESSION['rooms']      = $rooms;
}

// Redirect to payment page
header("Location: esewa_payment.php");
exit;
?>
