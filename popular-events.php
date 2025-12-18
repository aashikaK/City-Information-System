<?php
session_start();

// Protect admin pages
if(!isset($_SESSION['admin']) || $_SESSION['admin'] == ''){
    header("Location: admin_login.php");
    exit;
}

require "db.php";
include "navbar.php";

try {
    // Fetch all popular events
    $sql = "SELECT * FROM events WHERE is_popular = 1 ORDER BY event_date ASC";
    $stmt = $pdo->query($sql);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Popular Events</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

.page-header { background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1 { font-size:2.5rem; }

.events-container {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(280px,1fr));
    gap:20px;
    width:90%;
    margin:20px auto;
    justify-items:center;
}

.event-card {
    background:rgba(200, 218, 233, 1);
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 8px rgba(0,0,0,0.42);
    transition:0.3s;
    max-width:360px;
    width:100%;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow:0 6px 12px rgba(0,0,0,0.2);
}

.event-card strong { font-size:1.5rem; color:#222; }
.event-date { color:#555; font-size:0.95rem; }
.event-location { font-style:italic; color:#777; font-size:0.9rem; }

.popular-badge {
    display:inline-block;
    background:#ffe082;
    color:#333;
    font-weight:bold;
    padding:3px 6px;
    border-radius:5px;
    margin-left:5px;
    font-size:0.85rem;
}

.event-image {
    width:100%;
    height:180px;
    object-fit:contain;
    background:#e9eef3;
    border-radius:10px;
    margin-bottom:10px;
}

.footer {
    background:#3F84B1;
    color:white;
    text-align:center;
    padding:15px 20px;
    margin-top:30px;
}
</style>
</head>
<body>

<div class="page-header">
    <h1>Popular Events</h1>
</div>

<div class="events-container">
<?php
if ($events) {
    foreach ($events as $event) {
        echo "<div class='event-card'>";

        $img_path = !empty($event['image_path'])
            ? htmlspecialchars($event['image_path'])
            : 'images/events/default.jpg';

        echo "<img src='{$img_path}' class='event-image'>";
        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong>";
        echo "<span class='popular-badge'>Popular</span>";

        echo "<div class='event-date'>" . date("d M Y", strtotime($event['event_date'])) . "</div>";
        echo "<div class='event-location'>📍 " . htmlspecialchars($event['city']) .
             " - " . htmlspecialchars($event['location']) . "</div>";
        echo "<p>" . htmlspecialchars($event['description']) . "</p>";

        echo "</div>";
    }
}
?>
</div>

<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

</body>
</html>
