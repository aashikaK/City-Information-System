<?php
session_start();
require "db.php";

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// Get booking details from POST (sent from book_now.php)
$service_id = $_POST['service_id'] ?? null;
$category   = $_POST['category'] ?? null;
$total_price = $_POST['total_price'] ?? null;

if (!$service_id || !$category || !$total_price) {
    die("Invalid payment request");
}

// Fetch service name
$stmt = $pdo->prepare("SELECT name FROM city_services WHERE id=?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
$service_name = $service['name'] ?? 'Unknown Service';

// Get optional details
$appointment_date = $_POST['appointment_date'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date   = $_POST['end_date'] ?? null;
$rooms      = $_POST['rooms'] ?? 1;
$days       = $_POST['days'] ?? 1;

// Save in session for further processing
$_SESSION['service_id'] = $service_id;
$_SESSION['category']   = $category;
$_SESSION['total_price'] = $total_price;

if ($category == "Hospital") {
    $_SESSION['appointment_date'] = $appointment_date;
} elseif ($category == "Hotel") {
    $_SESSION['start_date'] = $start_date;
    $_SESSION['end_date']   = $end_date;
    $_SESSION['rooms']      = $rooms;
    $_SESSION['days']       = $days;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <meta charset="UTF-8">
    <style>
* {margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial;}
body {background:#f4f7fb; min-height:100vh; display:flex; justify-content:center; align-items:center;}
.payment-box {background:#fff; width:100%; max-width:450px; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.12); text-align:center;}
.payment-box h2 {margin-bottom:20px; color:#3F84B1;}
.payment-box p {margin:12px 0; font-size:1.05rem; color:#333;}
.payment-box strong {color:#000;}
.payment-box button {margin-top:20px; background:#3F84B1; color:white; border:none; padding:12px 22px; font-size:1rem; border-radius:6px; cursor:pointer; transition:0.3s ease;}
.payment-box button:hover {background:#2f6d93; transform:translateY(-2px);}
.payment-box button:active {transform:translateY(0);}
</style>
</head>
<body>
<div class="payment-box">
    <h2>Confirm Your Booking</h2>

    <p><strong>Service:</strong> <?php echo htmlspecialchars($service_name); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>

    <?php if($category == "Hospital"): ?>
        <p><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment_date); ?></p>
        <p><strong>Price:</strong> Rs. <?php echo htmlspecialchars($total_price); ?></p>

    <?php elseif($category == "Hotel"): ?>
        <p><strong>Check-in:</strong> <?php echo htmlspecialchars($start_date); ?></p>
        <p><strong>Check-out:</strong> <?php echo htmlspecialchars($end_date); ?></p>
        <p><strong>Rooms:</strong> <?php echo htmlspecialchars($rooms); ?></p>
        <p><strong>Days:</strong> <?php echo htmlspecialchars($days); ?></p>
        <p><strong>Total Price:</strong> Rs. <?php echo htmlspecialchars($total_price); ?></p>
    <?php endif; ?>

    <form method="POST" action="esewa_process.php">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="amount" value="<?php echo $total_price; ?>">
        <input type="hidden" name="category" value="<?php echo $category; ?>">

        <?php if($category == "Hospital"): ?>
            <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($appointment_date); ?>">
        <?php elseif($category == "Hotel"): ?>
            <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
            <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            <input type="hidden" name="rooms" value="<?php echo htmlspecialchars($rooms); ?>">
            <input type="hidden" name="days" value="<?php echo htmlspecialchars($days); ?>">
        <?php endif; ?>

        <button type="submit">Proceed to eSewa</button>
    </form>
</div>
</body>
</html>
