<?php
session_start();
require "db.php";

// Today’s date
$today = date('Y-m-d');

// 1. Release hospital rooms whose appointment date has passed
$stmt = $pdo->prepare("
    SELECT b.id, b.service_id, b.rooms 
    FROM bookings b
    JOIN city_services c ON b.service_id = c.id
    WHERE b.category='Hospital'
      AND b.start_date IS NOT NULL
      AND b.start_date < ?
      AND b.status='confirmed'
");
$stmt->execute([$today]);
$hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($hospitals as $booking) {
    // Reduce current_bookings by rooms booked
    $update = $pdo->prepare("UPDATE city_services SET current_bookings = current_bookings - ? WHERE id = ?");
    $update->execute([$booking['rooms'], $booking['service_id']]);

    // Optional: mark booking as released to avoid double processing
    $mark = $pdo->prepare("UPDATE bookings SET status='completed' WHERE id=?");
    $mark->execute([$booking['id']]);
}

// 2. Release hotel rooms whose checkout date has passed
$stmt = $pdo->prepare("
    SELECT b.id, b.service_id, b.rooms 
    FROM bookings b
    JOIN city_services c ON b.service_id = c.id
    WHERE b.category='Hotel'
      AND b.end_date IS NOT NULL
      AND b.end_date < ?
      AND b.status='confirmed'
");
$stmt->execute([$today]);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($hotels as $booking) {
    // Reduce current_bookings by rooms booked
    $update = $pdo->prepare("UPDATE city_services SET current_bookings = current_bookings - ? WHERE id = ?");
    $update->execute([$booking['rooms'], $booking['service_id']]);

    // Optional: mark booking as completed
    $mark = $pdo->prepare("UPDATE bookings SET status='completed' WHERE id=?");
    $mark->execute([$booking['id']]);
}

// echo "Capacity released successfully for today.";
?>
