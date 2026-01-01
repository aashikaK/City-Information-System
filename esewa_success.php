<?php
session_start();

// 1️⃣ Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// 2️⃣ Get payment data sent by eSewa
$pid       = $_POST['pid'] ?? null;    // the payment ID
$amt       = $_POST['amt'] ?? null;    // the amount paid
$refId     = $_POST['refId'] ?? null;  // eSewa transaction reference

// 3️⃣ Validate that we have all required data
if (!$pid || !$amt || !$refId) {
    die("Payment data missing. Something went wrong!");
}

// 4️⃣ Optional: check if PID matches the one stored in session
if ($pid != $_SESSION['pid']) {
    die("Payment ID mismatch!");
}

// 5️⃣ At this point, you can mark the booking as successful in your database
// Example (assuming $pdo is your PDO database connection):
require "db.php";

// Insert transaction into bookings table
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, service_id, pid, amount, ref_id, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $_SESSION['user_id'],       // who booked
    $_SESSION['service_id'],    // which service
    $pid,                       // unique booking/payment ID
    $amt,                       // amount
    $refId,                     // eSewa reference ID
    'success'                   // status
]);

// 6️⃣ Optionally, increase current bookings of the service
$stmt = $pdo->prepare("UPDATE city_services SET current_bookings = current_bookings + 1 WHERE id = ?");
$stmt->execute([$_SESSION['service_id']]);

// 7️⃣ Clear session pid to prevent re-submission
unset($_SESSION['pid']);
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

<div class="success">🎉 Payment Successful!</div>
<div class="info"><strong>Booking ID:</strong> <?php echo htmlspecialchars($pid); ?></div>
<div class="info"><strong>Amount Paid:</strong> Rs. <?php echo htmlspecialchars($amt); ?></div>
<div class="info"><strong>eSewa Ref ID:</strong> <?php echo htmlspecialchars($refId); ?></div>

<a href="issuetickets.php" class="button">Back to Services</a>

</body>
</html>
