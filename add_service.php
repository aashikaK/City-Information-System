<?php
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $city          = trim($_POST['city']);
    $category      = trim($_POST['category']);
    $name          = trim($_POST['name']);
    $description   = trim($_POST['description']);
    $contact_info  = trim($_POST['contact_info']);
    $location      = trim($_POST['location']);
    $booking_price = $_POST['booking_price'];
    $capacity      = $_POST['capacity'];

    // 🔍 CHECK DUPLICATE SERVICE (same city + same name)
   $check = $pdo->prepare("
    SELECT id FROM city_services 
    WHERE city = ? AND category = ? AND name = ?
    LIMIT 1
");
$check->execute([$city, $category, $name]);


    if ($check->rowCount() > 0) {
       $error = "This service already exists in this city under the same category.";

    } else {

        // Image upload (ONLY images/services/)
        $image_path = '';
        if (!empty($_FILES['image']['name'])) {

            $upload_dir = "images/services/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = time() . "_" . basename($_FILES['image']['name']);
            $image_path = $upload_dir . $image_name;

            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO city_services 
            (city, category, name, description, contact_info, location, image, booking_price, capacity, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        $stmt->execute([
            $city,
            $category,
            $name,
            $description,
            $contact_info,
            $location,
            $image_path,
            $booking_price,
            $capacity
        ]);

        header("Location: manage-services.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f7fb;
}
.container {
    max-width: 600px;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #4a90e2;
}
.error {
    background: #ffe6e6;
    color: #c00;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
    text-align: center;
}
input, textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
}
button {
    background: #4a90e2;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 6px;
    width: 100%;
    cursor: pointer;
}
button:hover {
    background: #357ab8;
}
</style>
</head>

<body>

<div class="container">
    <h2>Add City Service</h2>

    <!-- ❌ ERROR MESSAGE -->
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="city" placeholder="City" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>">
        <input type="text" name="category" placeholder="Category (Hospital, Transport, Hotel)" required value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
        <input type="text" name="name" placeholder="Service Name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <textarea name="description" rows="4" placeholder="Description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

        <input type="text" name="location" placeholder="Location" value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
        <input type="text" name="contact_info" placeholder="Contact Info" value="<?= htmlspecialchars($_POST['contact_info'] ?? '') ?>">

        <input type="number" name="capacity" placeholder="Capacity" value="<?= htmlspecialchars($_POST['capacity'] ?? '') ?>">
        <input type="number" step="0.01" name="booking_price" placeholder="Booking Price" value="<?= htmlspecialchars($_POST['booking_price'] ?? '') ?>">

        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Service</button>
    </form>
</div>

</body>
</html>
