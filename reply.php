<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit;
}

if (!isset($_GET['msg_id'])) {
    die("Invalid request");
}

$msg_id = (int)$_GET['msg_id'];

/* Fetch user message + profile */
$stmt = $pdo->prepare("
    SELECT w.*, p.profile_pic, p.full_name
    FROM write_us w
    LEFT JOIN user_profiles p ON p.user_id = w.user_id
    WHERE w.id = ?
");
$stmt->execute([$msg_id]);
$msg = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$msg) die("Message not found");

/* Handle admin reply */
if (isset($_POST['send_reply'])) {
    $reply = trim($_POST['reply']);

    if ($reply !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO message_replies (message_id, user_id, sender, reply)
            VALUES (?, ?, 'admin', ?)
        ");
        $stmt->execute([$msg_id, $msg['user_id'], $reply]);

        // stay in chat (like messenger)
        header("Location: reply.php?msg_id=".$msg_id);
        exit;
    }
}

/* Fetch chat history */
$stmt = $pdo->prepare("
    SELECT * FROM message_replies
    WHERE message_id=?
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
<title>Reply Message</title>

<style>
body{
    background:#e5ddd5;
    font-family:Segoe UI;
    padding:20px;
}

.chat-container{
    max-width:800px;
    margin:auto;
    background:#fff;
    border-radius:12px;
    display:flex;
    flex-direction:column;
    height:85vh;
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
}

/* Header */
.chat-header{
    padding:15px;
    background:#075e54;
    color:white;
    display:flex;
    align-items:center;
    gap:12px;
}
.chat-header img{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
}
.back-btn{
    color:white;
    text-decoration:none;
    font-size:20px;
    margin-right:10px;
}

/* Body */
.chat-body{
    flex:1;
    padding:15px;
    overflow-y:auto;
    background:#efeae2;
    display:flex;
    flex-direction:column;
}

/* Message bubbles */
.msg{
    max-width:70%;
    padding:10px 14px;
    border-radius:12px;
    margin-bottom:10px;
    font-size:14px;
}

.user-msg{
    background:white;
    align-self:flex-start;
}

.admin-msg{
    background:#dcf8c6;
    align-self:flex-end;
}

/* Footer */
.chat-footer{
    padding:10px;
    background:#f0f0f0;
    display:flex;
    gap:10px;
}
textarea{
    flex:1;
    resize:none;
    padding:10px;
    border-radius:20px;
    border:1px solid #ccc;
}
button{
    background:#25D366;
    border:none;
    color:white;
    padding:10px 20px;
    border-radius:20px;
    cursor:pointer;
}
button:hover{
    background:#1da851;
}
</style>
</head>

<body>

<div class="chat-container">

<!-- HEADER -->
<div class="chat-header">
    <a href="manage-messages.php" class="back-btn">←</a>
    <img src="<?= $userPic ?>">
    <div>
        <strong><?= htmlentities($msg['full_name'] ?? $msg['username']) ?></strong><br>
        <small><?= htmlentities($msg['email']) ?></small>
    </div>
</div>

<!-- CHAT BODY -->
<div class="chat-body" id="chatBody">

    <!-- User original message -->
    <div class="msg user-msg">
        <?= nl2br(htmlentities($msg['message'])) ?><br>
        <small><?= $msg['created_at'] ?></small>
    </div>

    <!-- Chat history -->
    <?php foreach ($replies as $r): ?>
        <?php if ($r['sender'] === 'admin'): ?>
            <div class="msg admin-msg">
                <?= nl2br(htmlentities($r['reply'])) ?><br>
                <small><?= $r['created_at'] ?></small>
            </div>
        <?php else: ?>
            <div class="msg user-msg">
                <?= nl2br(htmlentities($r['reply'])) ?><br>
                <small><?= $r['created_at'] ?></small>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

<!-- FOOTER -->
<form method="post" class="chat-footer">
    <textarea name="reply" placeholder="Type a reply..." required></textarea>
    <button type="submit" name="send_reply">Send</button>
</form>

</div>

<!-- AUTO SCROLL -->
<script>
    const chatBody = document.getElementById("chatBody");
    chatBody.scrollTop = chatBody.scrollHeight;
</script>

</body>
</html>
