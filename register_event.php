<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    // Not logged in
    header("Location: login.php");
    exit;
}

$username = $_SESSION['login'];
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

if (!$event_id) {
    header("Location: events.php");
    exit;
}

// Get user_id from username
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :uname");
$stmt->execute([':uname' => $username]);
$user_id = $stmt->fetchColumn();

if (!$user_id) {
    // User not found in DB
    header("Location: login.php");
    exit;
}

// Check if already registered
$check = $pdo->prepare("SELECT id FROM user_events WHERE user_id = :uid AND event_id = :eid");
$check->execute([':uid'=>$user_id, ':eid'=>$event_id]);
if ($check->rowCount() == 0) {
    // Register the user
    $insert = $pdo->prepare("INSERT INTO user_events (user_id, event_id, status) VALUES (:uid, :eid, 'registered')");
    $insert->execute([':uid'=>$user_id, ':eid'=>$event_id]);
}

// Redirect back to events page
header("Location: events.php");
exit;
?>
