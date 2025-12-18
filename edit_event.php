<?php
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage-events.php");
    exit;
}

$event_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: manage-events.php");
    exit;
}

// 3️⃣ Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['event_name'];
    $city = $_POST['city'];
    $location = $_POST['location'];
    $date = $_POST['event_date'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("
        UPDATE events 
        SET event_name = ?, city = ?, location = ?, event_date = ?, description = ?
        WHERE event_id = ?
    ");
    $stmt->execute([$name, $city, $location, $date, $description, $event_id]);

    header("Location: manage-events.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Event</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body { font-family: Arial, sans-serif; background:#f4f7fb; }
.container {
    max-width:600px;
    margin:30px auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h2 { text-align:center; color:#4a90e2; }
input, textarea {
    width:100%;
    padding:10px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:6px;
}
button {
    background:#4a90e2;
    color:white;
    padding:10px;
    border:none;
    border-radius:6px;
    width:100%;
    cursor:pointer;
}
button:hover { background:#357ab8; }
</style>
</head>

<body>
<div class="container">
    <h2>Edit Event</h2>

    <form method="POST">
        <input type="text" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>
        <input type="text" name="city" value="<?= htmlspecialchars($event['city']) ?>" required>
        <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
        <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required>
        <textarea name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>

        <button type="submit">Update Event</button>
    </form>
</div>
</body>
</html>
