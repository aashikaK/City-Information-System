<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require "db.php";

$ticket_count = 0;

if (isset($_SESSION['login'])) {
    $username = $_SESSION['login'];
    $stmt_user = $pdo->prepare("SELECT id FROM users WHERE username=?");
    $stmt_user->execute([$username]);
    $user_id = $stmt_user->fetchColumn();
    
    if ($user_id) {
        // Count only unread notifications
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id=? AND status='unread'");
        $stmt_count->execute([$user_id]);
        $ticket_count = (int)$stmt_count->fetchColumn();
    }
}
?>
