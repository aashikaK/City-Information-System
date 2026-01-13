<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$transaction_uuid = $_SESSION['transaction_uuid'] ?? 'N/A';
$amount           = $_SESSION['total_price'] ?? 'N/A';
$category         = strtolower($_SESSION['category'] ?? 'N/A');

if ($category == 'hospital') {
    $start_date = $_SESSION['appointment_date'] ?? null;
    $end_date   = null;
    $rooms      = 1;
} else { // hotel
    $start_date = $_SESSION['start_date'] ?? null;
    $end_date   = $_SESSION['end_date'] ?? null;
    $rooms      = $_SESSION['rooms'] ?? 1;
}

// Clear session (optional, because payment failed)
unset($_SESSION['transaction_uuid']);
unset($_SESSION['service_id']);
unset($_SESSION['total_price']);
unset($_SESSION['category']);
unset($_SESSION['appointment_date']);
unset($_SESSION['start_date']);
unset($_SESSION['end_date']);
unset($_SESSION['rooms']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <style>
        body { font-family: "Segoe UI", Arial; text-align:center; background:#f4f7fb; padding:50px; }
        .fail { color:#e74c3c; font-size:1.5rem; margin-bottom:20px; }
        .info { font-size:1rem; margin:10px 0; }
        a.button { display:inline-block; margin-top:20px; padding:10px 20px; background:#3F84B1; color:white; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>

<div class="fail"> Payment Failed!</div>
<div class="info"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></div>
<div class="info"><strong>Amount:</strong> Rs. <?php echo htmlspecialchars($amount); ?></div>

<?php if($category == 'hotel'): ?>
<div class="info"><strong>Check-in:</strong> <?php echo htmlspecialchars($start_date); ?></div>
<div class="info"><strong>Check-out:</strong> <?php echo htmlspecialchars($end_date); ?></div>
<div class="info"><strong>Rooms:</strong> <?php echo htmlspecialchars($rooms); ?></div>
<?php elseif($category == 'hospital'): ?>
<div class="info"><strong>Appointment Date:</strong> <?php echo htmlspecialchars($start_date); ?></div>
<?php endif; ?>

<a href="issuetickets.php" class="button">Back to Services</a>

</body>
</html>
