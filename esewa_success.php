<?php
session_start();


if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}


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

//  fake reference ID 
$refId = "UAT_" . strtoupper(substr(md5(time()), 0, 8));

require "db.php";

$username = $_SESSION['login'];
$sqlUser = "SELECT id FROM users WHERE username=?";
$stmtUser = $pdo->prepare($sqlUser);
$stmtUser->execute([$_SESSION['login']]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $userid = $userData['id'];

$stmt = $pdo->prepare("
    INSERT INTO bookings 
    (user_id, service_id, category, booking_date,payment_amount,payment_status, status)
    VALUES (?, ?, ?, NOW(), ?,?,?)
");

$stmt->execute([
    $userid,
    $service_id,
    $_SESSION['category'],  // you can fetch category from services table if needed
    $amount,
    'success',
    'pending'         // booking approved automatically
]);


// Update booking count
$stmt = $pdo->prepare("
    UPDATE city_services 
    SET current_bookings = current_bookings + 1
    WHERE id = ?
");
$stmt->execute([$service_id]);

//  Clear session
unset($_SESSION['transaction_uuid']);
unset($_SESSION['service_id']);
unset($_SESSION['amount']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <style>
        body { font-family: "Segoe UI", Arial; text-align:center; background:#f4f7fb; padding:50px; }
        .success { color:#3F84B1; font-size:1.5rem; margin-bottom:20px; }
        .info { font-size:1rem; margin:10px 0; }
        a.button { display:inline-block; margin-top:20px; padding:10px 20px; background:#3F84B1; color:white; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>

<div class="success"> Payment Successful!</div>
<div class="info"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></div>
<div class="info"><strong>Amount Paid:</strong> Rs. <?php echo htmlspecialchars($amount); ?></div>
<div class="info"><strong>Reference ID:</strong> <?php echo htmlspecialchars($refId); ?></div>

<a href="issuetickets.php" class="button">Back to Services</a>

</body>
</html>
