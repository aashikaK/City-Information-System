<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_GET['service_id'] ?? null;
$amount     = $_GET['amount'] ?? null;
if (!$service_id || !$amount) {
    die("Invalid payment request");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <meta charset="UTF-8">
</head>
<body>
