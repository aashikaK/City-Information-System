<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['login'];

// Get user_id from username
$user_stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$user_stmt->execute([':username' => $username]);
$user_id = $user_stmt->fetchColumn();

// Make sure event_id is provided
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Update status to 'cancelled' in user_events
    $stmt = $pdo->prepare("UPDATE user_events
SET status = 'cancelled'
WHERE user_id = :uid
  AND event_id = :eid
  AND status IN ('registered', 'pending')
");
    $stmt->execute([
        ':uid' => $user_id,
        ':eid' => $event_id
    ]);

   
    // Redirect back to event history page
    header("Location: eventhistory.php");
    exit;
} else {
    // If no event_id provided
    echo "Invalid request.";
}
?>
