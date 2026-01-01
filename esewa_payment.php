<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_GET['service_id'] ?? null;
$amount     = $_GET['amount'] ?? null;
