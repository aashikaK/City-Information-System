<?php
require "db.php";

// Fetch messages
$stmt = $pdo->query("SELECT * FROM write_us ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Email</th>
    <th>Subject</th>
    <th>Message</th>
    <th>Status</th>
    <th>Created At</th>
</tr>

<?php foreach($messages as $msg): ?>
<tr>
    <td><?php echo $msg['id']; ?></td>
    <td><?php echo htmlentities($msg['username']); ?></td>
    <td><?php echo htmlentities($msg['email']); ?></td>
    <td><?php echo htmlentities($msg['subject']); ?></td>
    <td><?php echo htmlentities($msg['message']); ?></td>
    <td><?php echo htmlentities($msg['status']); ?></td>
    <td><?php echo $msg['created_at']; ?></td>
</tr>
<?php endforeach; ?>

</table>
