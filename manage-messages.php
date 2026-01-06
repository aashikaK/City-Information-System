<?php
include "admin-navbar.php";
require "db.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}
if (!isset($_GET['from'])) {
    $stmt = $pdo->prepare(
        "UPDATE write_us SET status='pending' WHERE status='new'"
    );
    $stmt->execute();
}

/* -------------------------------------------------
   2. Handle manual status update
-------------------------------------------------- */
if (isset($_POST['update_status'])) {

    $msg_id = (int)$_POST['msg_id'];

    if (isset($_POST['replied'])) {
        $new_status = 'replied';
    } elseif (isset($_POST['read'])) {
        $new_status = 'read';
    } else {
        $new_status = 'pending';
    }

    $stmt = $pdo->prepare(
        "UPDATE write_us SET status=? WHERE id=?"
    );
    $stmt->execute([$new_status, $msg_id]);

    header("Location: manage-messages.php");
    exit;
}

/* -------------------------------------------------
   3. Fetch messages
-------------------------------------------------- */
$stmt = $pdo->query(
    "SELECT * FROM write_us ORDER BY created_at DESC"
);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Messages</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:"Segoe UI",Arial;}
body{background:#f4f7fb;padding:20px;}

h2{text-align:center;margin-bottom:25px;color:#4a90e2;}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
th,td{
    padding:12px 14px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#4a90e2;
    color:white;
}
tr:hover{background:#f1f1f1;}

.status-new{
    background:#dc3545;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}
.status-pending{
    background:#ffc107;
    color:#212529;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}
.status-read{
    background:#28a745;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}
.status-replied{
    background:#007bff;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}

button{
    background:#4a90e2;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:6px;
    cursor:pointer;
}
button:hover{background:#3a78c2;}

@media(max-width:768px){
    table,thead,tbody,tr,td,th{display:block;}
    th{display:none;}
    tr{margin-bottom:15px;}
    td{
        display:flex;
        justify-content:space-between;
        padding:8px;
    }
    td::before{
        content:attr(data-label);
        font-weight:bold;
        color:#555;
    }
}
</style>
</head>

<body>

<h2><i class="fas fa-envelope"></i> User Messages</h2>

<?php if (!$messages): ?>
<p style="text-align:center;color:#555;">No messages found.</p>
<?php else: ?>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Email</th>
    <th>Subject</th>
    <th>Message</th>
    <th>Status</th>
    <th>Read</th>
    <th>Replied</th>
    <th>Sent At</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php foreach ($messages as $msg): ?>
<tr>

<td data-label="ID"><?= $msg['id'] ?></td>
<td data-label="User"><?= htmlentities($msg['username']) ?></td>
<td data-label="Email"><?= htmlentities($msg['email']) ?></td>
<td data-label="Subject"><?= htmlentities($msg['subject']) ?></td>
<td data-label="Message"><?= nl2br(htmlentities($msg['message'])) ?></td>

<td data-label="Status">
<?php
if ($msg['status'] === 'new')
    echo '<span class="status-new">New</span>';
elseif ($msg['status'] === 'pending')
    echo '<span class="status-pending">Pending</span>';
elseif ($msg['status'] === 'read')
    echo '<span class="status-read">Read</span>';
elseif ($msg['status'] === 'replied')
    echo '<span class="status-replied">Replied</span>';
?>
</td>

<form method="post">
<td data-label="Read">
<input type="checkbox" name="read"
<?= ($msg['status']=='read' || $msg['status']=='replied') ? 'checked' : '' ?>>
</td>

<td data-label="Replied">
<input type="checkbox" name="replied"
<?= ($msg['status']=='replied') ? 'checked' : '' ?>>
</td>

<td data-label="Sent At"><?= $msg['created_at'] ?></td>

<td data-label="Action">
<input type="hidden" name="msg_id" value="<?= $msg['id'] ?>">
<button type="submit" name="update_status">Update</button>
</td>
</form>

</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php endif; ?>

</body>
</html>
