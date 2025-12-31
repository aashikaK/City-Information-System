<?php
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
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

    // Image upload (optional)
    // Image upload (optional)
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {

    // Make city folder inside tourism (replace spaces with underscores)
    $city_folder = preg_replace('/\s+/', '_', strtolower($city));
    $upload_dir = "images/tourism/$city_folder/";

    // Create folder if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Save the image
    $image_path = $upload_dir . time() . "_" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
}


    $stmt = $pdo->prepare("
        INSERT INTO tourism (city, place_name, category, description, population, area, contact_info, image, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
    ");
    $stmt->execute([$city, $place_name, $category, $description, $population, $area, $contact_info, $image_path]);

    header("Location: manage-tourism.php");
    exit;
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
    <h2>Add Tourism Place</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="place_name" placeholder="Place Name" required>
        <input type="text" name="category" placeholder="Category">
        <textarea name="description" rows="4" placeholder="Description"></textarea>
        <input type="number" name="population" placeholder="Population">
        <input type="text" name="area" placeholder="Area">
        <input type="text" name="contact_info" placeholder="Contact Info">
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Place</button>
    </form>
</div>
</body>
</html>
