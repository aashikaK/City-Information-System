<?php
include "admin-navbar.php";

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == ''){
    header("Location: signin.php");
    exit;
}

require "db.php";

try {
    // Fetch all user_events with event and user info
    $sql = "SELECT ue.status, u.username, u.email, e.event_name, e.city, e.location, e.event_date, e.image_path
        FROM user_events ue
        JOIN users u ON ue.user_id = u.id
        JOIN events e ON ue.event_id = e.event_id
        WHERE ue.status IN ('registered', 'attended')
        ORDER BY e.event_date ASC";

    $stmt = $pdo->query($sql);
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Registered Events</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

.page-header { background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1 { font-size:2.5rem; }

.events-container {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(300px,1fr));
    gap:20px;
    width:90%;
    margin:20px auto;
}

.event-card {
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 4px 8px rgba(0,0,0,0.15);
    transition:0.3s;
    max-width:400px;
    width:100%;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow:0 6px 12px rgba(0,0,0,0.25);
}

.event-card img {
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}

.event-card strong { font-size:1.4rem; color:#222; display:block; margin-bottom:5px; }
.event-info { font-size:0.9rem; color:#555; margin-bottom:5px; }
.user-info { 
    

font-size:0.85rem; color:#333; margin-top:10px; background:#eef3f9; padding:8px; border-radius:6px; }

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
    <h1>Registered Events by Users</h1>
</div>

<div class="events-container">
<?php
if ($registrations) {
    foreach ($registrations as $reg) {
        $img_path = !empty($reg['image_path']) ? htmlspecialchars($reg['image_path']) : 'images/events/default.jpg';

        echo "<div class='event-card'>";
        echo "<img src='{$img_path}' alt='Event Image'>";
        echo "<strong>" . htmlspecialchars($reg['event_name']) . "</strong>";
        echo "<div class='event-info'>📍 " . htmlspecialchars($reg['city']) . " - " . htmlspecialchars($reg['location']) . "</div>";
        echo "<div class='event-info'>📅 " . date("d M Y", strtotime($reg['event_date'])) . "</div>";

        echo "<div class='user-info'>";
        echo "<strong>User:</strong> " . htmlspecialchars($reg['username']) . "<br>";
        echo "<strong>Email:</strong> " . htmlspecialchars($reg['email']) . "<br>";
        $status_color = ($reg['status'] == 'attended') ? '#4caf50' : '#f39c12'; // green for attended, orange for registered
echo "<span style='display:inline-block; padding:4px 8px; border-radius:5px; background:{$status_color}; color:white; margin-top:5px;'>{$reg['status']}</span>";

        echo "<strong>Status:</strong> " . ucfirst($reg['status']);
        echo "</div>";

        echo "</div>";
    }
} else {
    echo "<p style='text-align:center; color:#777;'>No users have registered for events yet.</p>";
}
?>
</div>

<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

</body>
</html>
