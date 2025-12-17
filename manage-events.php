<?php
include "admin-navbar.php";
require "db.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
body { background:#f4f7fb; font-family:"Segoe UI", Arial, sans-serif; margin:0; }
.container {
    max-width:1100px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
h2 {
    text-align:center;
    color:#4a90e2;
    margin-bottom:20px;
}
.add-btn {
    display:inline-block;
    margin-bottom:15px;
    padding:8px 15px;
    background:#4a90e2;
    color:white;
    border-radius:8px;
    text-decoration:none;
    font-size:1rem; 
}
.add-btn:hover { background:#357ab8; }

table {
    width:100%;
    border-collapse:collapse;
}
th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
th {
    background:#4a90e2;
    color:white;
}

td img {
    max-width:80px; 
    height:auto;
    border-radius:4px;
    vertical-align:middle; 
}

.action-btn {
    padding:6px 10px; 
    border:none;
    border-radius:6px; /* Unified border-radius */
    margin:2px;
    color:white;
    text-decoration:none;
    display:inline-block; /* Ensures consistent size */
    font-size:0.9rem; /* Added font-size for consistency */
    line-height:1.2;
}
.action-btn:hover {
    opacity:0.9;
}

.edit { background:#4a90e2; }
.delete { background:red; }
.popular { background:green; } /* Changed to green for a positive action */
.unpopular { background:gray; }

/* RESPONSIVE */
@media (max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    th { display:none; } /* Hide headers on small screens */
    td {
        padding:10px;
        border:none; /* Remove individual cell borders in block view */
        border-bottom:1px solid #eee; /* Add a separator between rows */
        text-align:left; /* Left align for better mobile readability */
        position:relative;
        padding-left:140px; /* Space for the data-label */
    }
    /* Add a clean border for the last cell of each row */
    tr:last-child td { border-bottom: none; }
    
    td::before { 
        content: attr(data-label); 
        font-weight:bold; 
        display:block; /* Changed to block for full width alignment */
        position:absolute;
        left:10px;
        width:120px; 
        text-align:left;
        top: 10px;
    }
    
    /* Center the actions and image for better layout */
    td[data-label="Image"] { text-align:center; padding-left:10px; }
    td[data-label="Image"]::before { display:inline-block; position:static; width:auto; margin-right:10px; }
    td[data-label="Actions"] { text-align:center; padding-left:10px; }
    td[data-label="Actions"]::before { display:inline-block; position:static; width:auto; margin-right:10px; }
    
    td img { max-width:100px; height:auto; }
}
</style>
</head>
<body>

<div class="container">
    <h2>Manage Events</h2>
    <a href="add-events.php" class="add-btn"><i class="fas fa-plus"></i> Add Event</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Location</th>
                <th>Date</th>
                <th>Description</th>
                <th>Image</th>
                <th>Popular</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($events as $event): ?>
            <tr>
                <td data-label="ID"><?= $event['event_id'] ?></td>
                <td data-label="Name"><?= htmlspecialchars($event['event_name']) ?></td>
                <td data-label="City"><?= htmlspecialchars($event['city']) ?></td>
                <td data-label="Location"><?= htmlspecialchars($event['location']) ?></td>
                <td data-label="Date"><?= date("d M Y", strtotime($event['event_date'])) ?></td>
                <td data-label="Description"><?= htmlspecialchars(substr($event['description'],0,50)) ?>...</td>
                <td data-label="Image"><img src="<?= $event['image_path'] ?: 'images/events/default.jpg' ?>" alt="Event Image"></td>
                <td data-label="Popular"><?= $event['is_popular'] ? "Yes" : "No" ?></td>
                <td data-label="Actions">
                    <a href="edit_event.php?id=<?= $event['event_id'] ?>" class="action-btn edit">Edit</a>
                    <a href="delete_event.php?id=<?= $event['event_id'] ?>" class="action-btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php if($event['is_popular']): ?>
                        <a href="toggle_popular.php?id=<?= $event['event_id'] ?>&action=unpopular" class="action-btn unpopular">Unmark</a>
                    <?php else: ?>
                        <a href="toggle_popular.php?id=<?= $event['event_id'] ?>&action=popular" class="action-btn popular">Mark Popular</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
