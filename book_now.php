<?php
session_start();
require "db.php";

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// Get service and category from POST (from issuetickets.php)
$service_id = $_POST['service_id'] ?? null;
$category   = $_POST['category'] ?? null;

if (!$service_id || !$category) {
    die("Invalid booking request.");
}

// Fetch service details
$stmt = $pdo->prepare("SELECT * FROM city_services WHERE id=?");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Service not found.");
}

$available_capacity = $service['capacity'] - $service['current_bookings'];
$username = $_SESSION['login'];

// Fetch user ID
$stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("User not found.");
$user_id = $user['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Service - City Info System</title>
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial;}
body {background:#f4f7fb; min-height:100vh; display:flex; justify-content:center; align-items:center;}
.booking-box {background:#fff; max-width:500px; width:100%; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.12);}
.booking-box h2 {color:#3F84B1; margin-bottom:20px;}
.booking-box label {display:block; margin-top:15px;}
.booking-box input, .booking-box select {width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ccc;}
.booking-box .total-price {margin-top:15px; font-size:1.2rem; font-weight:bold; color:#333;}
.booking-box button {margin-top:20px; background:#3F84B1; color:white; border:none; padding:12px 20px; font-size:1rem; border-radius:6px; cursor:pointer;}
.booking-box button:hover {background:#2f6d93;}
</style>
</head>
<body>

<div class="booking-box">
    <h2>Booking: <?php echo htmlspecialchars($service['name']); ?></h2>
    <form method="POST" action="esewa_payment.php" id="bookingForm">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="category" value="<?php echo $category; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <?php if ($category == "Hospital"): ?>
            <label>Appointment Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
            <p class="total-price">Price: Rs. <?php echo $service['booking_price']; ?></p>
            <input type="hidden" name="total_price" value="<?php echo $service['booking_price']; ?>">

        <?php elseif ($category == "Hotel"): ?>
            <label>Check-in Date:</label>
            <input type="date" name="start_date" id="start_date" min="<?php echo date('Y-m-d'); ?>" required>

            <label>Check-out Date:</label>
            <input type="date" name="end_date" id="end_date" required>

            <label>Rooms:</label>
            <input type="number" name="rooms" id="rooms" min="1" max="<?php echo $available_capacity; ?>" value="1" required>

            <p class="total-price">Total Price: Rs. <span id="totalPrice"><?php echo $service['booking_price']; ?></span></p>
            <input type="hidden" name="total_price" id="total_price_input" value="<?php echo $service['booking_price']; ?>">
        <?php endif; ?>

        <button type="submit">Make Payment</button>
    </form>
</div>

<script>
// Hotel dynamic price calculation
<?php if ($category == "Hotel"): ?>
const pricePerRoom = <?php echo $service['booking_price']; ?>;
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');
const roomsInput = document.getElementById('rooms');
const totalPriceSpan = document.getElementById('totalPrice');
const totalPriceInput = document.getElementById('total_price_input');

function updateCheckoutMin() {
    if (startDateInput.value) {
        const start = new Date(startDateInput.value);
        start.setDate(start.getDate() + 1); // checkout must be next day
        const minDate = start.toISOString().split('T')[0];
        endDateInput.min = minDate;

        // if checkout is before new min, reset
        if (endDateInput.value && endDateInput.value < minDate) {
            endDateInput.value = minDate;
        }
    }
}

function updateTotalPrice() {
    if (!startDateInput.value || !endDateInput.value) return;

    const start = new Date(startDateInput.value);
    const end = new Date(endDateInput.value);

    let diffMs = end - start;
    let nights = Math.round(diffMs / (1000*60*60*24));
    if (nights < 1) nights = 1;

    let rooms = parseInt(roomsInput.value) || 1;
    const total = pricePerRoom * nights * rooms;
    totalPriceSpan.textContent = total;
    totalPriceInput.value = total;
}

startDateInput.addEventListener('change', () => {
    updateCheckoutMin();
    updateTotalPrice();
});
endDateInput.addEventListener('change', updateTotalPrice);
roomsInput.addEventListener('input', updateTotalPrice);
<?php endif; ?>
</script>

</body>
</html>
