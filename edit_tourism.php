<?php
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage_tourism.php");
    exit;
}

$tourism_id = (int)$_GET['id'];

// Fetch the tourism record
$stmt = $pdo->prepare("SELECT * FROM tourism WHERE id = ?");
$stmt->execute([$tourism_id]);
$place = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$place) {
    header("Location: manage_tourism.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $city = $_POST['city'];
    $place_name = $_POST['place_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $population = $_POST['population'];
    $area = $_POST['area'];
    $contact_info = $_POST['contact_info'];

    $stmt = $pdo->prepare("
        UPDATE tourism 
        SET city = ?, place_name = ?, category = ?, description = ?, population = ?, area = ?, contact_info = ?
        WHERE id = ?
    ");
    $stmt->execute([$city, $place_name, $category, $description, $population, $area, $contact_info, $tourism_id]);

    header("Location: manage-tourism.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Tourism Place</title>
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
input, textarea { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:6px; }
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
    <h2>Edit Tourism Place</h2>

    <form method="POST">
        <input type="text" name="city" value="<?= htmlspecialchars($place['city']) ?>" placeholder="City" required>
        <input type="text" name="place_name" value="<?= htmlspecialchars($place['place_name']) ?>" placeholder="Place Name" required>
        <input type="text" name="category" value="<?= htmlspecialchars($place['category']) ?>" placeholder="Category">
        <textarea name="description" rows="4" placeholder="Description"><?= htmlspecialchars($place['description']) ?></textarea>
        <input type="number" name="population" value="<?= htmlspecialchars($place['population']) ?>" placeholder="Population">
        <input type="text" name="area" value="<?= htmlspecialchars($place['area']) ?>" placeholder="Area">
        <input type="text" name="contact_info" value="<?= htmlspecialchars($place['contact_info']) ?>" placeholder="Contact Info">

        <button type="submit">Update Place</button>
    </form>
</div>
</body>
</html>
