<?php
include "admin-navbar.php";
require "db.php";

/* Fetch all issued tickets */
$stmt = $pdo->query("
    SELECT 
        t.*, 
        u.username
    FROM tickets t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
");
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Issued Tickets</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    background:#f4f7fb;
    font-family:"Segoe UI", Arial, sans-serif;
}
.container {
    max-width:1400px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align:center;
    color:#4a90e2;
    margin-bottom:20px;
}
table {
    width:100%;
    border-collapse:collapse;
    font-size:0.95rem;
}
th, td {
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
th {
    background:#f0f4fa;
}
.badge {
    padding:4px 8px;
    border-radius:6px;
    font-size:0.85rem;
    color:white;
}
.hospital { background:#28a745; }
.hotel { background:#3f84b1; }

.btn {
    padding:6px 10px;
    border-radius:6px;
    color:white;
    text-decoration:none;
    font-size:0.85rem;
}
.view { background:#4a90e2; }
</style>
</head>

<body>

<div class="container">
<h2>Issued Tickets History</h2>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Ticket No</th>
    <th>User</th>
    <th>Category</th>
    <th>Service Name</th>
    <th>Issued Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php if (!$tickets): ?>
<tr>
    <td colspan="8">No tickets issued yet.</td>
</tr>
<?php endif; ?>

<?php foreach ($tickets as $t): ?>
<tr>
    <td><?= $t['id'] ?></td>
    <td><?= htmlspecialchars($t['ticket_number']) ?></td>
    <td><?= htmlspecialchars($t['username']) ?></td>
    <td>
        <span class="badge <?= $t['category'] ?>">
            <?= ucfirst($t['category']) ?>
        </span>
    </td>
    <td><?= htmlspecialchars($t['service_name']) ?></td>
    <td><?= $t['issued_date'] ?> <?= $t['issued_time'] ?></td>
    <td><?= ucfirst($t['status']) ?></td>
    <td>
        <a class="btn view" 
           href="ticket_view.php?booking_id=<?= $t['booking_id'] ?>" 
           target="_blank">
           View Ticket
        </a>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</body>
</html>
