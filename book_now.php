<?php
require "db.php";
session_start();

// (Assume user is logged in)
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // make sure you store this at login
    $service_id = intval($_POST['service_id']);
    $category = $_POST['category'];

    // 1️⃣ Fetch service details
    $stmt = $pdo->prepare("SELECT * FROM city_services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo "<script>alert('Invalid service selected.'); window.history.back();</script>";
        exit;
    }

    // 2️⃣ Check capacity
    if ($service['current_bookings'] >= $service['capacity']) {
        echo "<script>alert('Sorry, this {$category} is already full!'); window.history.back();</script>";
        exit;
    }

    // 3️⃣ Insert booking record
    $insert = $pdo->prepare("INSERT INTO bookings (user_id, service_id, category, booking_date, status) 
                             VALUES (?, ?, ?, CURDATE(), 'pending')");
    $insert->execute([$user_id, $service_id, strtolower($category)]);

    // 4️⃣ Update current bookings count
    $update = $pdo->prepare("UPDATE city_services 
                             SET current_bookings = current_bookings + 1 
                             WHERE id = ?");
    $update->execute([$service_id]);

    // 5️⃣ Placeholder for eSewa integration (later)
    // 👉 When you add payment, place eSewa code here
    // Example:
    // header("Location: esewa_payment.php?booking_id={$pdo->lastInsertId()}");

    echo "<script>alert('Booking successful! You can proceed to payment later.'); window.location='booking.php';</script>";
    exit;
}
?>
