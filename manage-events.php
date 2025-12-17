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
    border-radius:6px; 
    margin:2px;
    color:white;
    text-decoration:none;
    display:inline-block; 
    font-size:0.9rem; 
    line-height:1.2;
}
.action-btn:hover {
    opacity:0.9;
}

.edit { background:#4a90e2; }
.delete { background:red; }
.popular { background:green; } 
.unpopular { background:gray; }


@media (max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    th { display:none; } 
    td {
        padding:10px;
        border:none; 
        border-bottom:1px solid #eee; 
        text-align:left; 
        position:relative;
        padding-left:140px; 
    }
    tr:last-child td { border-bottom: none; }
    
    td::before { 
        content: attr(data-label); 
        font-weight:bold; 
        display:block; 
        position:absolute;
        left:10px;
        width:120px; 
        text-align:left;
        top: 10px;
    }
    
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

    <!-- EDIT -->
    <a href="edit_event.php?id=<?= $event['event_id'] ?>" class="action-btn edit">
        Edit
    </a>

    <!-- DELETE -->
    <form method="POST" style="display:inline;" 
          onsubmit="return confirm('Are you sure?')">
        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
        <input type="hidden" name="action" value="delete">
        <button class="action-btn delete">Delete</button>
    </form>

    <!-- POPULAR TOGGLE -->
    <form method="POST" style="display:inline;">
        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">

        <?php if ($event['is_popular']) { ?>
            <input type="hidden" name="action" value="unpopular">
            <button class="action-btn unpopular">Unmark</button>
        <?php } else { ?>
            <input type="hidden" name="action" value="popular">
            <button class="action-btn popular">Mark Popular</button>
        <?php } ?>
    </form>

</td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
