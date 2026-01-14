<?php
session_start();
require "db.php";

if(!isset($_SESSION['login'])){
    echo json_encode(['success'=>false]);
    exit;
}

$username = $_SESSION['login'];

// Get user ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmt->execute([$username]);
$user_id = $stmt->fetchColumn();

if($user_id){
    $pdo->prepare("UPDATE notifications SET status='read' WHERE user_id=? AND status='unread'")
        ->execute([$user_id]);
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false]);
}
?>
