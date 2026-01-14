<?php
require "db.php";
session_start();

// Make sure user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['login'];

// Get user_id from username
$user_stmt = $pdo->prepare("SELECT id FROM users WHERE username = :uname");
$user_stmt->execute([':uname' => $username]);
$user_id = $user_stmt->fetchColumn();

if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Get event_id from POST
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
if (!$event_id) {
    header("Location: events.php");
    exit;
}

// Check if record already exists
$check_stmt = $pdo->prepare("SELECT id, status FROM user_events WHERE user_id = :uid AND event_id = :eid");
$check_stmt->execute([':uid' => $user_id, ':eid' => $event_id]);
$record = $check_stmt->fetch(PDO::FETCH_ASSOC);

if ($record) {
    if ($record['status'] == 'cancelled') {
        // Re-register
        $update = $pdo->prepare("UPDATE user_events SET status='pending', registered_at=NOW() WHERE id=:id");
        $update->execute([':id' => $record['id']]);
    }
    // If already registered or attended, do nothing
} else {
    // New registration
    $insert = $pdo->prepare("INSERT INTO user_events (user_id, event_id, status) VALUES (:uid, :eid, 'pending')");
    $insert->execute([':uid' => $user_id, ':eid' => $event_id]);
}

// Redirect back (you can choose where: events.php or eventhistory.php)
header("Location: eventhistory.php");
exit;
?>
