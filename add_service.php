<?php
session_start();
require "db.php";
include "admin-navbar.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: signin.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $city          = $_POST['city'];
    $category      = $_POST['category'];
    $name          = $_POST['name'];
    $description   = $_POST['description'];
    $contact_info  = $_POST['contact_info'];
    $location      = $_POST['location'];
    $booking_price = $_POST['booking_price'];
    $capacity      = $_POST['capacity'];

    // Image upload (ONLY images/services/)
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {

        $upload_dir = "images/services/";

        // Create folder if not exists
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

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="category" placeholder="Category (Hospital, Transport, Hotel)" required>
        <input type="text" name="name" placeholder="Service Name" required>

        <textarea name="description" rows="4" placeholder="Description"></textarea>

        <input type="text" name="location" placeholder="Location">
        <input type="text" name="contact_info" placeholder="Contact Info">

        <input type="number" name="capacity" placeholder="Capacity">
        <input type="number" step="0.01" name="booking_price" placeholder="Booking Price">

        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Service</button>
    </form>
</div>

</body>
</html>
