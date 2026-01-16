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

// Fetch all messages and admin replies together chronologically
// We'll combine them into one array sorted by created_at
$chatItems = [];

// Fetch user messages
$stmt = $pdo->prepare("
    SELECT id as msg_id, message as content, created_at, 'user' as sender
    FROM write_us
    WHERE user_id=?
");
$stmt->execute([$user_id]);
$userMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch admin replies for this user
$stmt = $pdo->prepare("
    SELECT message_id, reply as content, created_at, 'admin' as sender
    FROM message_replies
    WHERE user_id=?
");
$stmt->execute([$user_id]);
$adminReplies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine all messages
foreach ($userMessages as $um) $chatItems[] = $um;
foreach ($adminReplies as $ar) $chatItems[] = $ar;

// Sort by created_at ascending
usort($chatItems, function($a, $b) {
    return strtotime($a['created_at']) - strtotime($b['created_at']);
});
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
    display:flex;
    flex-direction:column;
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
    background:white;
    align-self:flex-start;
}
.admin-msg {
    background:#dcf8c6;
    align-self:flex-end;
}
.message-reply-to {
    font-size:12px;
    color:#888;
    margin-bottom:3px;
    padding-left:5px;
    border-left:2px solid #ccc;
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

<!-- Chat header: shows admin profile -->
<div class="chat-header">
    <a href="dashboard.php" class="back-btn">←</a> <!-- Added back arrow -->
    <img src="<?= $adminPic ?>" alt="Admin">
    <div>
        <strong>Admin</strong><br>
        <small>Your Chat History</small>
    </div>
</div>


<div class="chat-body" id="chatBody">
    <?php if (!$chatItems): ?>
        <div class="info-msg">No messages yet. To ask something, use <strong>Write Us</strong>.</div>
    <?php else: ?>
        <?php foreach ($chatItems as $item): ?>
            <?php if ($item['sender'] === 'user'): ?>
                <div class="user-msg">
                    <?= nl2br(htmlentities($item['content'])) ?><br>
                    <small><?= $item['created_at'] ?></small>
                </div>
            <?php else: ?>
                <div class="admin-msg">
                    <?php
                    // Show which user message this admin reply is for
                    $msg_id = $item['message_id'];
                    $originalMsg = array_filter($userMessages, fn($m) => $m['msg_id']==$msg_id);
                    $originalMsg = reset($originalMsg);
                    if ($originalMsg):
                    ?>
                        <div class="message-reply-to">Replying to your message: <?= nl2br(htmlentities($originalMsg['content'])) ?></div>
                    <?php endif; ?>
                    <?= nl2br(htmlentities($item['content'])) ?><br>
                    <small><?= $item['created_at'] ?></small>
                </div>
            <?php endif; ?>
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
