<?php
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $city        = trim($_POST['city']);
    $place_name  = trim($_POST['place_name']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $population  = $_POST['population'];
    $area        = trim($_POST['area']);
    $contact_info= trim($_POST['contact_info']);

    // 🔍 DUPLICATE CHECK (city + place_name)
    $check = $pdo->prepare("
        SELECT id FROM tourism
        WHERE city = ? AND place_name = ? 
        LIMIT 1
    ");
    $check->execute([$city, $place_name]);

    if ($check->rowCount() > 0) {
        $error = "This place already exists in the selected city.";
    } else {

        // Image upload (optional)
        $image_path = '';
        if (!empty($_FILES['image']['name'])) {

            $city_folder = preg_replace('/\s+/', '_', strtolower($city));
            $upload_dir = "images/tourism/$city_folder/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_path = $upload_dir . time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO tourism 
            (city, place_name, category, description, population, area, contact_info, image, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        $stmt->execute([
            $city,
            $place_name,
            $category,
            $description,
            $population,
            $area,
            $contact_info,
            $image_path
        ]);

        header("Location: manage-tourism.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Tourism Place</title>
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
.error {
    background:#ffe6e6;
    color:#c00;
    padding:10px;
    border-radius:6px;
    margin-bottom:10px;
    text-align:center;
}
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
    <h2>Add Tourism Place</h2>

    <!-- ❌ ERROR MESSAGE -->
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="city" placeholder="City" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
        <input type="text" name="place_name" placeholder="Place Name" required value="<?= htmlspecialchars($_POST['place_name'] ?? '') ?>">
        <input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
        <textarea name="description" rows="4" placeholder="Description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        <input type="number" name="population" placeholder="Population" value="<?= htmlspecialchars($_POST['population'] ?? '') ?>">
        <input type="text" name="area" placeholder="Area" value="<?= htmlspecialchars($_POST['area'] ?? '') ?>">
        <input type="text" name="contact_info" placeholder="Contact Info" value="<?= htmlspecialchars($_POST['contact_info'] ?? '') ?>">
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Place</button>
    </form>
</div>
</body>
</html>
