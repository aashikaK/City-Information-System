<?php
session_start();
require "db.php";

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$username = $_SESSION['login'];

// Get user_id
$stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) die("Invalid session");
$user_id = $user['id'];

// Default admin pic for chat header
$adminPic = 'images/admin-profile.png';

// Fetch all messages of this user
$stmt = $pdo->prepare("
    SELECT w.*, p.profile_pic, p.full_name
    FROM write_us w
    LEFT JOIN user_profiles p ON p.user_id = w.user_id
    WHERE w.user_id = ?
    ORDER BY w.created_at ASC
");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all replies and group by message_id
$replies = [];
$stmt = $pdo->prepare("
    SELECT * FROM message_replies 
    WHERE user_id=? 
    ORDER BY created_at ASC
");
$stmt->execute([$user_id]);
$allReplies = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($allReplies as $r) {
    $replies[$r['message_id']][] = $r;
}
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
    width:50px;
    height:50px;
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
.message-reply-to {
    font-size:12px;
    color:#888;
    margin-bottom:3px;
    padding-left:5px;
    border-left:2px solid #ccc;
}
</style>
</head>
<body>

<div class="chat-container">

<!-- Chat header: shows admin profile -->
<div class="chat-header">
    <img src="<?= $adminPic ?>" alt="Admin">
    <div>
        <strong>Admin</strong><br>
        <small>Your Chat History</small>
    </div>
</div>

<div class="chat-body" id="chatBody">
    <?php if (!$messages): ?>
        <div class="info-msg">No messages yet. To ask something, use <strong>Write Us</strong>.</div>
    <?php else: ?>
        <?php foreach ($messages as $msg): ?>
            <!-- Show which message this admin reply is for -->
            <?php if (!empty($replies[$msg['id']])): ?>
                <?php foreach ($replies[$msg['id']] as $r): ?>
                    <div class="admin-msg">
                        <div class="message-reply-to">Replying to your message: <?= nl2br(htmlentities($msg['message'])) ?></div>
                        <?= nl2br(htmlentities($r['reply'])) ?><br>
                        <small><?= $r['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- User original message on right -->
            <div class="user-msg">
                <?= nl2br(htmlentities($msg['message'])) ?><br>
                <small><?= $msg['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Info message -->
<div class="info-msg">
    To ask more questions or clarifications, please send a new message using <strong>Write Us</strong>.
</div>

<a href="write-us.php" class="back-btn">Ask Admin Again</a>

</div>

<script>
// Scroll chat to bottom on page load
const chatBody = document.getElementById('chatBody');
chatBody.scrollTop = chatBody.scrollHeight;
</script>

</body>
</html>
