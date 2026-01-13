<?php
include "admin-navbar.php";
require "db.php";

// Protect admin
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    die("Invalid Request");
}

$booking_id = (int)$_GET['booking_id'];

/* 1. Get booking details */
$stmt = $pdo->prepare("
    SELECT b.*, u.username, cs.name AS service_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN city_services cs ON b.service_id = cs.id
    WHERE b.id = ? AND b.status = 'confirmed'
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found or already processed.");
}

/* 2. Generate ticket number */
$categoryCode = strtoupper($booking['category']);
$date = date("Ymd");
$ticket_number = "CIS-$categoryCode-$date-" . str_pad($booking_id, 5, "0", STR_PAD_LEFT);

/* 3. Insert ticket */
$stmt = $pdo->prepare("
    INSERT INTO tickets (
        ticket_number, booking_id, user_id, service_id, category,
        service_name, issued_date, issued_time, authority_signature
    ) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), CURTIME(), ?)
");
$stmt->execute([
    $ticket_number,
    $booking_id,
    $booking['user_id'],
    $booking['service_id'],
    $booking['category'],
    $booking['service_name'],
    "Authorized Admin"
]);

/* 4. Mark booking completed */
$pdo->prepare("UPDATE bookings SET status='completed' WHERE id=?")
    ->execute([$booking_id]);

/* 5. Redirect to ticket */
header("Location: ticket_view.php?booking_id=$booking_id");
exit;
