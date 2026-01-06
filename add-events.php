<?php
include "admin-navbar.php";
require "db.php";

// Protect admin pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $event_name  = trim($_POST['event_name']);
    $city        = trim($_POST['city']);
    $location    = trim($_POST['location']);
    $event_date  = $_POST['event_date'];
    $description = $_POST['description'];
    $is_popular  = isset($_POST['is_popular']) ? 1 : 0;

    // 🔍 CHECK DUPLICATE EVENT (same name + city + location)
    $check = $pdo->prepare("
        SELECT id FROM events 
        WHERE event_name = ? AND city = ? AND location = ?
        LIMIT 1
    ");
    $check->execute([$event_name, $city, $location]);

    if ($check->rowCount() > 0) {
        $error = "This event already exists in the same city and location.";
    } else {

        // Handle image upload
        $image_path = '';
        if (!empty($_FILES['image']['name'])) {
            $img_name = time() . '_' . $_FILES['image']['name'];
            $target_dir = 'images/events/';

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $img_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            $image_path = $target_file;
        }

        // Insert into DB
        $stmt = $pdo->prepare("
            INSERT INTO events 
            (event_name, city, location, event_date, description, image_path, is_popular, created_at)
            VALUES (:name, :city, :location, :date, :desc, :img, :pop, NOW())
        ");

        $stmt->execute([
            ':name' => $event_name,
            ':city' => $city,
            ':location' => $location,
            ':date' => $event_date,
            ':desc' => $description,
            ':img' => $image_path,
            ':pop' => $is_popular
        ]);

        header("Location: manage-events.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Event - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<style>
body { background:#f4f7fb; font-family:"Segoe UI", Arial, sans-serif; margin:0; }
.container { max-width:700px; margin:30px auto; background:white; padding:25px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#4a90e2; margin-bottom:20px; }
form label { display:block; margin-top:15px; font-weight:bold; }
form input, form textarea { width:100%; padding:8px 10px; margin-top:5px; border-radius:6px; border:1px solid #ccc; }
form input[type="checkbox"] { width:auto; }
form button { margin-top:20px; padding:10px 15px; background:#4a90e2; color:white; border:none; border-radius:8px; cursor:pointer; }
form button:hover { background:#357ab8; }
.error {
    background:#ffe6e6;
    color:#c00;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
}
</style>
</head>
<body>

<div class="container">
    <h2>Add New Event</h2>

    <!-- ❌ DUPLICATE ERROR -->
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Event Name</label>
        <input type="text" name="event_name" required>

        <label>City</label>
        <input type="text" name="city" required>

        <label>Location</label>
        <input type="text" name="location" required>

        <label>Event Date</label>
        <input type="date" name="event_date" required>

        <label>Description</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Image</label>
        <input type="file" name="image" accept="image/*">

        <label>
            <input type="checkbox" name="is_popular"> Mark as Popular
        </label>

        <button type="submit"><i class="fas fa-plus"></i> Add Event</button>
    </form>
</div>

</body>
</html>
