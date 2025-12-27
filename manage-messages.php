<?php
include "admin-navbar.php";  
require "db.php";

// Fetch all messages from write_us table
$stmt = $pdo->query("SELECT * FROM write_us ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Messages - CIS Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb;  }

h2 { text-align:center; margin-bottom:25px; color:#4a90e2; }

/* Table Styles */
table {
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    border-radius:10px;
    overflow:hidden;
}
table th, table td {
    padding:12px 15px;
    text-align:left;
    border-bottom:1px solid #ddd;
}
table th {
    background:#4a90e2;
    color:white;
}
table tr:hover {
    background:#f1f1f1;
}

/* Status badges */
.status-pending {
    background:#ffc107;
    color:#212529;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}
.status-read {
    background:#28a745;
    color:white;
    padding:4px 8px;
    border-radius:6px;
    font-weight:bold;
}

/* Responsive for mobile */
@media(max-width:768px){
    table, thead, tbody, th, td, tr {
        display:block;
    }
    table tr {
        margin-bottom:15px;
        background:white;
        box-shadow:0 5px 10px rgba(0,0,0,0.05);
        border-radius:8px;
        padding:10px;
    }
    table th {
        display:none;
    }
    table td {
        display:flex;
        justify-content:space-between;
        padding:8px 10px;
        border-bottom:none;
    }
    table td::before {
        content: attr(data-label);
        font-weight:bold;
        color:#555;
    }
}
</style>
</head>
<body>

<h2><i class="fas fa-envelope"></i> User Messages</h2>

<?php if(count($messages) == 0): ?>
    <p style="text-align:center; color:#555;">No messages found.</p>
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
            <th>Sent At</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($messages as $msg): ?>
        <tr>
            <td data-label="ID"><?php echo $msg['id']; ?></td>
            <td data-label="User"><?php echo htmlentities($msg['username']); ?></td>
            <td data-label="Email"><?php echo htmlentities($msg['email']); ?></td>
            <td data-label="Subject"><?php echo htmlentities($msg['subject']); ?></td>
            <td data-label="Message"><?php echo htmlentities($msg['message']); ?></td>
            <td data-label="Status">
                <?php if($msg['status'] == 'pending'): ?>
                    <span class="status-pending">Pending</span>
                <?php else: ?>
                    <span class="status-read">Read</span>
                <?php endif; ?>
            </td>
            <td data-label="Sent At"><?php echo $msg['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</body>
</html>
