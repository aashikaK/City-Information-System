<?php
require "db.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['login'];

// get user id
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user_id = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $rating = $_POST['rating'] ?: null;
    $review = $_POST['review'] ?: null;
    $note = $_POST['personal_note'] ?: null;
    $is_favorite = isset($_POST['is_favorite']) ? (int)$_POST['is_favorite'] : 0;

    $update = $pdo->prepare("UPDATE user_events 
                             SET rating = ?, review = ?, personal_note = ?,is_favorite=?
                             WHERE user_id = ? AND event_id = ?");
    $update->execute([ $rating, $review, $note,$is_favorite,$user_id,$event_id]);

    // redirect back to event history
    header("Location: eventhistory.php");
    exit;
}
?>
