<?php
require "db.php";
include "navbar.php";


if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['login'];

// 2️⃣ Get user ID
$user_stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$user_stmt->execute([$username]);
$user_id = $user_stmt->fetchColumn();

// 3️⃣ Fetch bookings for this user
$bookings_stmt = $pdo->prepare("
    SELECT 
        b.id, b.service_id, b.booking_date, b.payment_amount, b.payment_status, b.status, 
        s.name AS service_name, s.location AS service_location, s.image
    FROM bookings b
    JOIN city_services s ON b.service_id = s.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");
$bookings_stmt->execute([$user_id]);
$bookings = $bookings_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Booking History</title>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }
.page-header { background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1 { font-size:2.5rem; }
.events-container { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:20px; width:90%; margin:20px auto; justify-items:center; }
.event-card { background:rgba(200, 218, 233, 1); padding:15px; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.42); transition:0.3s;max-width:360px; width:100%; }
.event-card:hover { transform: translateY(-5px); box-shadow:0 6px 12px rgba(0,0,0,0.2); }
.event-card strong { font-size:1.2rem; color:#222; }
.event-image { width:100%; height:180px; object-fit:cover; border-radius:10px; margin-bottom:10px; }
.event-date { color:#555; font-size:0.95rem; margin-top:5px; }
.event-location { font-style:italic; color:#777; font-size:0.9rem; }
.payment-status { display:inline-block; padding:5px 10px; border-radius:5px; margin-top:10px; color:white; font-weight:bold; }
.success { background:#3F84B1; }
.failed { background:#e74c3c; }
.cancelled { background:#555; }
.footer { background:#3F84B1; color:white; text-align:center; padding:15px 20px; margin-top:30px; }

[data-aos] { opacity: 1 !important; transform: none !important; }

@media(max-width:768px){ 
.events-container { 
grid-template-columns:1fr;
 } 
 }
</style>
</head>
<body>

<div class="page-header" data-aos="fade-down">
    <h1>My Booking History</h1>
</div>

<div class="events-container">
<?php
if ($bookings) {
    $delay = 0;
    foreach ($bookings as $b) {
        echo "<div class='event-card' data-aos='fade-up' data-aos-delay='{$delay}'>";
        
        // Service image, name & location
$img_path = !empty($b['image_path']) ? htmlspecialchars($b['image']) : 'images/default_service.jpg';
echo "<img src='{$img_path}' class='event-image' alt='Service Image'>";

        echo "<strong>" . htmlspecialchars($b['service_name']) . "</strong>";
        echo "<div class='event-location'>📍 " . htmlspecialchars($b['service_location']) . "</div>";

        // Booking date
        echo "<div class='event-date'>Booking Date: " . date("d M Y", strtotime($b['booking_date'])) . "</div>";

        // Payment amount & status
        $status_class = $b['payment_status'] === 'success' ? 'success' : ($b['payment_status'] === 'failed' ? 'failed' : 'cancelled');
        echo "<div class='payment-status {$status_class}'>";
        echo "Payment: " . ucfirst($b['payment_status']);
        echo "</div>";

        // Booking status
        echo "<div class='event-date'>Booking Status: " . ucfirst($b['status']) . "</div>";

        $delay += 100;
        echo "</div>";
    }
} else {
    echo "<p style='text-align:center; color:#777;'>You have no bookings yet.</p>";
}
?>
</div>

<div class="footer">
    &copy; 2026 City Information System. All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration:800, once:true });
  window.addEventListener('load', AOS.refresh);
</script>

</body>
</html>
