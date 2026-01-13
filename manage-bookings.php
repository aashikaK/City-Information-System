<?php
include "admin-navbar.php";
require "db.php";

// Protect admin pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

// Handle status update
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['pending','confirmed', 'cancelled','completed'])) { // include all possible statuses
        
    if ($action === 'cancelled') {
    // First, get booking info
    $stmtBooking = $pdo->prepare("SELECT category, service_id, rooms FROM bookings WHERE id = ?");
    $stmtBooking->execute([$id]);
    $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        $reduce = 1; // default for hospital
        if ($booking['category'] === 'hotel') {
            $reduce = (int)$booking['rooms'];
        }

        // Reduce current_bookings in city_services, but not below 0
        $stmtUpdate = $pdo->prepare("
            UPDATE city_services 
            SET current_bookings = GREATEST(current_bookings - ?, 0)
            WHERE id = ?
        ");
        $stmtUpdate->execute([$reduce, $booking['service_id']]);
    }
}

    
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$action, $id]);
    }

    header("Location: manage-bookings.php");
    exit;
}

// Fetch bookings with user info
$stmt = $pdo->query("
    SELECT b.*, u.username 
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    ORDER BY b.booking_date DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Bookings</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body { background:#f4f7fb; font-family:"Segoe UI", Arial, sans-serif; }
.container { max-width:1400px; margin:30px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#4a90e2; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; font-size:0.95rem; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#f0f4fa; }
.status-pending { color:#d18b00; font-weight:bold; }
.status-confirmed { color:green; font-weight:bold; }
.status-cancelled { color:red; font-weight:bold; }
.status-completed { color:#3F84B1; font-weight:bold; }

.btn { padding:6px 10px; border-radius:6px; color:white; text-decoration:none; font-size:0.85rem; margin:2px; display:inline-block;}
.confirm { background:#28a745; }
.cancel { background:#dc3545; }
.completed { background:#3F84B1; }
.disabled { background:#aaa; cursor:not-allowed; }
</style>
</head>
<body>

<div class="container">
<h2>Manage Bookings</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Category</th>
    <th>Service ID</th>
    <th>Booking Date</th>
    <th>Amount</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Rooms</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php if (!$bookings): ?>
<tr>
    <td colspan="12">No bookings found.</td>
</tr>
<?php endif; ?>

<?php foreach ($bookings as $b): ?>
<tr>
    <td><?= $b['id'] ?></td>
    <td><?= htmlspecialchars($b['username']) ?></td>
    <td><?= ucfirst($b['category']) ?></td>
    <td><?= $b['service_id'] ?></td>
    <td><?= $b['booking_date'] ?></td>
    <td>Rs. <?= $b['payment_amount'] ?></td>
    <td><?= htmlspecialchars($b['payment_status']) ?></td>

    <td class="status-<?= $b['status'] ?>">
        <?= ucfirst($b['status']) ?>
    </td>

    <!-- Show optional booking info -->
    <td><?= $b['category'] === 'hospital' ? htmlspecialchars($b['start_date']) : htmlspecialchars($b['start_date']) ?></td>
    <td><?= $b['category'] === 'hotel' ? htmlspecialchars($b['end_date']) : '-' ?></td>
    <td><?= $b['category'] === 'hotel' ? $b['rooms'] : 1 ?></td>

    <td>
        <?php if ($b['status'] === 'pending'): ?>
            <a class="btn confirm" href="?action=confirmed&id=<?= $b['id'] ?>">Confirm</a>
            <a class="btn cancel" href="?action=cancelled&id=<?= $b['id'] ?>">Cancel</a>
        <?php elseif($b['status'] === 'confirmed'): ?>
            <a class="btn completed" href="?action=completed&id=<?= $b['id'] ?>">Mark Completed</a>
        <?php else: ?>
            <span class="btn disabled">No Action</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</body>
</html>
