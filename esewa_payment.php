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
<h2>Confirm Your Payment</h2>

<p>
    <strong>Service ID:</strong> <?php echo htmlspecialchars($service_id); ?>
</p>

<p>
    <strong>Amount:</strong> Rs. <?php echo htmlspecialchars($amount); ?>
</p>
<form method="POST" action="esewa_process.php">
    <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">

    <button type="submit">Proceed to eSewa</button>
</form>
