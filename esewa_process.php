<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_POST['service_id'] ?? null;
$amount     = $_POST['amount'] ?? null;

if (!$service_id || !$amount) {
    die("Invalid payment request");
}
?>
