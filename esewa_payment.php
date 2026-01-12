<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// Get booking details from session
$service_id  = $_SESSION['service_id'] ?? null;
$category    = $_SESSION['category'] ?? null;
$total_price = $_SESSION['total_price'] ?? null;
$days        = $_SESSION['days'] ?? 1;

// For hotel
$rooms      = $_SESSION['rooms'] ?? null;
$start_date = $_SESSION['start_date'] ?? null;
$end_date   = $_SESSION['end_date'] ?? null;

// For hospital
$appointment_date = $_SESSION['appointment_date'] ?? null;

if (!$service_id || !$total_price || !$category) {
    die("Invalid payment request");
}

// Fetch service name
require "db.php";
$stmt = $pdo->prepare("SELECT name FROM city_services WHERE id=?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
$service_name = $service['name'] ?? 'Unknown Service';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <meta charset="UTF-8">
    <style>
        * {margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial;}
        body {background:#f4f7fb; min-height:100vh; display:flex; justify-content:center; align-items:center;}
        .payment-box {background:#fff; max-width:450px; width:100%; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.12); text-align:center;}
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
    <h2>Confirm Your Payment</h2>

    <p><strong>Service:</strong> <?php echo htmlspecialchars($service_name); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>

    <?php if($category == "Hospital"): ?>
        <p><strong>Appointment Date:</strong> <?php echo date('d-m-Y', strtotime($appointment_date)); ?></p>
        <p><strong>Price:</strong> Rs. <?php echo $total_price; ?></p>

    <?php elseif($category == "Hotel"): ?>
        <p><strong>Check-in:</strong> <?php echo date('d-m-Y', strtotime($start_date)); ?></p>
        <p><strong>Check-out:</strong> <?php echo date('d-m-Y', strtotime($end_date)); ?></p>
        <p><strong>Rooms:</strong> <?php echo $rooms; ?></p>
        <p><strong>Days:</strong> <?php echo $days; ?></p>
        <p><strong>Total Price:</strong> Rs. <?php echo $total_price; ?></p>
    <?php endif; ?>

    <form method="POST" action="esewa_process.php">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="amount" value="<?php echo $total_price; ?>">
        <input type="hidden" name="category" value="<?php echo $category; ?>">

        <?php if($category=="Hospital"): ?>
            <input type="hidden" name="appointment_date" value="<?php echo $appointment_date; ?>">
        <?php elseif($category=="Hotel"): ?>
            <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
            <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
            <input type="hidden" name="rooms" value="<?php echo $rooms; ?>">
            <input type="hidden" name="days" value="<?php echo $days; ?>">
        <?php endif; ?>

        <button type="submit">Proceed to eSewa</button>
    </form>
</div>
</body>
</html>
