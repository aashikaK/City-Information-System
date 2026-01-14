<?php
include "admin-navbar.php";
require "db.php";

// Protect admin pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

// Handle status updates
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['confirmed','cancelled'])) { // only pending actions
        $stmt = $pdo->prepare("UPDATE user_events SET status = ? WHERE id = ?");
        $stmt->execute([$action, $id]);
        header("Location: pending-userEvents.php");
        exit;
    }
}

// Fetch **only pending registrations**
$stmt = $pdo->query("
    SELECT ue.id, ue.status, u.username, u.email, e.event_name, e.city, e.location, e.event_date
    FROM user_events ue
    JOIN users u ON ue.user_id = u.id
    JOIN events e ON ue.event_id = e.event_id
    WHERE ue.status = 'pending'
    ORDER BY e.event_date ASC
");
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pending Event Registrations</title>
<style>
body { font-family:Arial, sans-serif; background:#f4f7fb; }
.container { max-width:1200px; margin:30px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#4a90e2; margin-bottom:20px; }

table { width:100%; border-collapse:collapse; font-size:0.95rem; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#f0f4fa; }
.status-pending { color:#d18b00; font-weight:bold; }

.btn { padding:6px 10px; border-radius:6px; color:white; text-decoration:none; font-size:0.85rem; margin:2px; display:inline-block;}
.confirm { background:#28a745; }
.cancel { background:#dc3545; }
</style>
</head>
<body>
<div class="container">
<h2>Pending Event Registrations</h2>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Email</th>
    <th>Event Name</th>
    <th>City</th>
    <th>Location</th>
    <th>Event Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php if (!$registrations): ?>
<tr><td colspan="9">No pending registrations.</td></tr>
<?php endif; ?>
<?php foreach ($registrations as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['username']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= htmlspecialchars($r['event_name']) ?></td>
    <td><?= htmlspecialchars($r['city']) ?></td>
    <td><?= htmlspecialchars($r['location']) ?></td>
    <td><?= date("d M Y", strtotime($r['event_date'])) ?></td>
    <td class="status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></td>
    <td>
        <a class="btn confirm" href="?action=confirmed&id=<?= $r['id'] ?>">Confirm</a>
        <a class="btn cancel" href="?action=cancelled&id=<?= $r['id'] ?>">Cancel</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</body>
</html>
