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

    $update = $pdo->prepare("UPDATE user_events 
                             SET rating = :rating, review = :review, personal_note = :note
                             WHERE user_id = :uid AND event_id = :eid");
    $update->execute([
        ':rating' => $rating,
        ':review' => $review,
        ':note'   => $note,
        ':uid'    => $user_id,
        ':eid'    => $event_id
    ]);

    // redirect back to event history
    header("Location: eventhistory.php");
    exit;
}
?>
