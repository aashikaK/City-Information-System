<?php
session_start();
require "db.php";

// Optional: check admin session
if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// Update all new messages to pending
$stmt = $pdo->prepare("UPDATE write_us SET status='pending' WHERE status='new'");
$stmt->execute();

echo "done";
