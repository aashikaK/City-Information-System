<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

if (
    !isset($_SESSION['transaction_uuid']) ||
    !isset($_SESSION['service_id']) ||
    !isset($_SESSION['total_price'])
) {
    die("Invalid session payment data");
}

require "db.php";

$transaction_uuid = $_SESSION['transaction_uuid'];
$service_id       = $_SESSION['service_id'];
$amount           = $_SESSION['total_price'];

// Fake reference ID
$refId = "UAT_" . strtoupper(substr(md5(time()), 0, 8));

// Fetch user id
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmtUser->execute([$_SESSION['login']]);
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
$userid = $userData['id'];

// Prepare optional fields
$category = strtolower($_SESSION['category']); // 'hospital' or 'hotel'

if ($category == 'hospital') {
    $start_date = $_SESSION['appointment_date'] ?? null;
    $end_date   = null;
    $rooms      = 1;
} else { // hotel
    $start_date = $_SESSION['start_date'] ?? null;
    $end_date   = $_SESSION['end_date'] ?? null;
    $rooms      = $_SESSION['rooms'] ?? 1;
}

// Insert booking including optional fields
$stmt = $pdo->prepare("
    INSERT INTO bookings 
    (user_id, service_id, category, booking_date, payment_amount, payment_status, status, start_date, end_date, rooms)
    VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $userid,
    $service_id,
    $category,
    $amount,
    'success',
    'pending',
    $start_date,
    $end_date,
    $rooms
]);

// Update booking count
$stmt = $pdo->prepare("
    UPDATE city_services 
    SET current_bookings = current_bookings + ?
    WHERE id = ?
");
$stmt->execute([$rooms, $service_id]);

// Clear session
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
    <title>Payment Successful</title>
    <style>
        body { font-family: "Segoe UI", Arial; text-align:center; background:#f4f7fb; padding:50px; }
        .success { color:#3F84B1; font-size:1.5rem; margin-bottom:20px; }
        .info { font-size:1rem; margin:10px 0; }
         .notice { font-size:1rem; color:#555; margin-top:20px; background:#eaf2fb; padding:15px; border-radius:8px; display:inline-block; max-width:500px; }
        a.button { display:inline-block; margin-top:20px; padding:10px 20px; background:#3F84B1; color:white; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>

<div class="success"> Payment Successful!</div>
<div class="info"><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></div>
<div class="info"><strong>Amount Paid:</strong> Rs. <?php echo htmlspecialchars($amount); ?></div>
<div class="info"><strong>Reference ID:</strong> <?php echo htmlspecialchars($refId); ?></div>

<?php if($category == 'hotel'): ?>
<div class="info"><strong>Check-in:</strong> <?php echo htmlspecialchars($start_date); ?></div>
<div class="info"><strong>Check-out:</strong> <?php echo htmlspecialchars($end_date); ?></div>
<div class="info"><strong>Rooms:</strong> <?php echo htmlspecialchars($rooms); ?></div>
<?php else: ?>
<div class="info"><strong>Appointment Date:</strong> <?php echo htmlspecialchars($start_date); ?></div>
<?php endif; ?>

<div class="notice">
    Your ticket is being generated. Please wait a few minutes. <br>
    You will be notified as soon as your official ticket is ready.
</div>


<a href="issuetickets.php" class="button">Back to Services</a>

</body>
</html>
