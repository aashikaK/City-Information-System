<?php
session_start();
require "db.php";

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$username=$_SESSION['login'];
$sql="select id from users where username=?";
$stmt=$pdo->prepare($sql);
$stmt->execute([$username]);
$user_id = $stmt->fetch(PDO::FETCH_ASSOC); ;
if (!$user_id) die("Invalid session");

// Check msg_id
if (!isset($_GET['msg_id'])) die("Invalid request");
$msg_id = (int)$_GET['msg_id'];

// Fetch original message and admin replies
$stmt = $pdo->prepare("
    SELECT w.*, u.username, p.profile_pic, p.full_name
    FROM write_us w
    JOIN users u ON w.user_id = u.id
    LEFT JOIN user_profiles p ON p.user_id = u.id
    WHERE w.id = ? AND w.user_id = ?
");
$stmt->execute([$msg_id, $user_id]);
$msg = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$msg) die("Message not found");

// Fetch all replies
$stmt = $pdo->prepare("
    SELECT * FROM message_replies
    WHERE message_id = ?
    ORDER BY created_at ASC
");
$stmt->execute([$msg_id]);
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userPic = $msg['profile_pic'] ?: 'images/default-user.png';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Chat with Admin</title>
<style>
body {
    background:#e5ddd5;
    font-family:Segoe UI, sans-serif;
    padding:20px;
}
.chat-container {
    max-width:800px;
    margin:auto;
    background:#fff;
    border-radius:12px;
    display:flex;
    flex-direction:column;
    height:85vh;
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
}
.chat-header {
    padding:15px;
    background:#075e54;
    color:white;
    display:flex;
    align-items:center;
    gap:12px;
    border-radius:12px 12px 0 0;
}
.chat-header img {
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
}
.chat-body {
    flex:1;
    padding:15px;
    overflow-y:auto;
    background:#efeae2;
}
.user-msg, .admin-msg {
    max-width:70%;
    padding:10px 14px;
    border-radius:12px;
    margin-bottom:10px;
    font-size:14px;
    word-wrap:break-word;
}
.user-msg {
    background:#dcf8c6;
    align-self:flex-end;
}
.admin-msg {
    background:white;
    align-self:flex-start;
}
.info-msg {
    text-align:center;
    font-style:italic;
    color:#555;
    padding:10px;
    margin-top:15px;
}
.back-btn {
    margin:10px 15px;
    display:inline-block;
    text-decoration:none;
    color:white;
    background:#25D366;
    padding:8px 16px;
    border-radius:20px;
}
.back-btn:hover {
    background:#1da851;
}
</style>
</head>
<body>

<div class="chat-container">

<div class="chat-header">
    <img src="<?= $userPic ?>">
    <div>
        <strong><?= htmlentities($msg['full_name'] ?? $msg['username']) ?></strong><br>
        <small><?= htmlentities($msg['email']) ?></small>
    </div>
</div>

<div class="chat-body">
    <!-- User original message -->
    <div class="user-msg">
        <?= nl2br(htmlentities($msg['message'])) ?><br>
        <small><?= $msg['created_at'] ?></small>
    </div>

    <!-- Admin replies -->
    <?php foreach ($replies as $r): ?>
        <div class="admin-msg">
            <?= nl2br(htmlentities($r['reply'])) ?><br>
            <small><?= $r['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
</div>

<!-- Info message for user -->
<div class="info-msg">
    If you have more questions or need clarification, please send a new message using <strong>Write Us</strong>.
</div>

<a href="write-us.php" class="back-btn">Ask Admin Again</a>

</div>

</body>
</html>
