<?php
session_start();

// 1️⃣ Check session
if (
    !isset($_SESSION['transaction_uuid']) ||
    !isset($_SESSION['service_id']) ||
    !isset($_SESSION['amount'])
) {
    die("Invalid session payment data");
}

$transaction_uuid = $_SESSION['transaction_uuid'];
$service_id       = $_SESSION['service_id'];
$amount           = $_SESSION['amount'];

//  fake reference ID for failed payment
$refId = "FAIL_" . strtoupper(substr(md5(time()), 0, 8));

require "db.php";

// Get user ID
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmtUser->execute([$_SESSION['login']]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
$userid = $userData['id'];

// Insert failed booking
$stmt = $pdo->prepare("
    INSERT INTO bookings 
    (user_id, service_id, category, booking_date, payment_amount, payment_status, status)
    VALUES (?, ?, ?, NOW(), ?, ?, ?)
");

$stmt->execute([
    $userid,
    $service_id,
    $_SESSION['category'] ?? 'unknown', // fallback if category not set
    $amount,
    'failed',
    'cancelled'  // failed payment → automatically cancelled
]);

// Clear session
unset($_SESSION['transaction_uuid']);
unset($_SESSION['service_id']);
unset($_SESSION['amount']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <style>
        body { font-family: "Segoe UI", Arial; text-align:center; background:#f4f7fb; padding:50px; }
        .failed { color:#e74c3c; font-size:1.5rem; margin-bottom:20px; }
        .info { font-size:1rem; margin:10px 0; }
        a.button { display:inline-block; margin-top:20px; padding:10px 20px; background:#3F84B1; color:white; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>

<div class="failed">⚠️ Payment Failed!</div>
<div class="info"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></div>
<div class="info"><strong>Amount:</strong> Rs. <?php echo htmlspecialchars($amount); ?></div>
<div class="info"><strong>Reference ID:</strong> <?php echo htmlspecialchars($refId); ?></div>

<a href="issuetickets.php" class="button">Back to Services</a>

</body>
</html>
