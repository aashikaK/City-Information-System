<?php
session_start();
require "db.php";

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['login'];

// Get user ID
$stmt_user = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmt_user->execute([$username]);
$user_id = $stmt_user->fetchColumn();

if (!$user_id) die("User not found.");

// Handle actions: delete or pin/unpin
if (isset($_GET['action'], $_GET['id'])) {
    $notif_id = (int)$_GET['id'];
    if ($_GET['action'] === 'delete') {
        $pdo->prepare("DELETE FROM notifications WHERE id=? AND user_id=?")
            ->execute([$notif_id, $user_id]);
    } elseif ($_GET['action'] === 'pin') {
        $pdo->prepare("UPDATE notifications SET pinned = NOT pinned WHERE id=? AND user_id=?")
            ->execute([$notif_id, $user_id]);
    }
    header("Location: notification.php");
    exit;
}

// Fetch notifications: pinned first, then newest
$stmt_notif = $pdo->prepare("
    SELECT n.*, t.ticket_number, t.booking_id, t.service_name, t.category
    FROM notifications n
    LEFT JOIN tickets t ON n.type='ticket' AND n.reference_id = t.booking_id
    WHERE n.user_id = ?
    ORDER BY pinned DESC, created_at DESC
");
$stmt_notif->execute([$user_id]);
$notifications = $stmt_notif->fetchAll(PDO::FETCH_ASSOC);

// Mark all unread as read once viewed
$pdo->prepare("UPDATE notifications SET status='read' WHERE user_id=? AND status='unread'")
    ->execute([$user_id]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications</title>
<style>
body { font-family:"Segoe UI", Arial; background:#f4f7fb; padding:20px; }
.container { max-width:700px; margin:auto; }
h2 { color:#3F84B1; text-align:center; margin-bottom:20px; }

/* Pinned section */
h3 { margin-top:20px; color:#4a90e2; }

/* Card */
.card { background:white; border-radius:10px; padding:12px 15px; margin-bottom:12px;
       box-shadow:0 2px 6px rgba(0,0,0,0.1); position:relative; max-height:120px; overflow:auto; }
.card.unread { border-left:4px solid #ff4d4d; }
.card h4 { margin:0 0 6px 0; font-size:16px; }
.card small { color:gray; font-size:12px; }
.card .actions { position:absolute; top:8px; right:8px; }
.card .actions a { margin-left:8px; color:#3F84B1; text-decoration:none; font-size:14px; }
.card .actions a:hover { color:#ff4d4d; }

/* Download button */
.btn-download { display:inline-block; padding:5px 10px; background:#4a90e2; color:white; border-radius:6px; text-decoration:none; margin-top:6px; font-size:13px; }
.btn-download:hover { background:#3678c3; }

/* Scrollable notifications list */
.notifications-list { max-height:400px; overflow-y:auto; padding-right:5px; }
</style>
</head>
<body>

<div class="container">
<h2>My Notifications</h2>

<?php
// Separate pinned and normal notifications
$pinned = array_filter($notifications, fn($n) => $n['pinned']);
$normal = array_filter($notifications, fn($n) => !$n['pinned']);
?>

<?php if($pinned): ?>
    <h3>Pinned Notifications</h3>
    <div class="notifications-list">
        <?php foreach($pinned as $n): ?>
            <div class="card <?= $n['status']=='unread'?'unread':'' ?>">
                <div class="actions">
                    <a href="?action=pin&id=<?= $n['id'] ?>" title="Unpin">📌</a>
                    <a href="?action=delete&id=<?= $n['id'] ?>" title="Delete" onclick="return confirm('Delete this notification?');">🗑️</a>
                </div>
                <h4><?= htmlspecialchars($n['title']) ?> <?= $n['pinned'] ? '📌' : '' ?></h4>
                <small><?= $n['created_at'] ?></small>
                <?php if($n['type']=='ticket' && $n['booking_id']): ?>
                    <br>
                    <a class="btn-download" href="ticket_view.php?booking_id=<?= $n['booking_id'] ?>" target="_blank">
                        View & Download Ticket
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if($normal): ?>
    <h3>Notifications</h3>
    <div class="notifications-list">
        <?php foreach($normal as $n): ?>
            <div class="card <?= $n['status']=='unread'?'unread':'' ?>">
                <div class="actions">
                    <a href="?action=pin&id=<?= $n['id'] ?>" title="Pin">📌</a>
                    <a href="?action=delete&id=<?= $n['id'] ?>" title="Delete" onclick="return confirm('Delete this notification?');">🗑️</a>
                </div>
                <h4><?= htmlspecialchars($n['title']) ?> <?= $n['pinned'] ? '📌' : '' ?></h4>
                <small><?= $n['created_at'] ?></small>
                <?php if($n['type']=='ticket' && $n['booking_id']): ?>
                    <br>
                    <a class="btn-download" href="ticket_view.php?booking_id=<?= $n['booking_id'] ?>" target="_blank">
                        View & Download Ticket
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if(empty($notifications)): ?>
    <p style="color:gray; text-align:center;">No notifications yet.</p>
<?php endif; ?>

</div>

</body>
</html>
