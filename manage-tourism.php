<?php
include "admin-navbar.php";
require "db.php";

if (!isset($_SESSION['admin']) || $_SESSION['admin'] == '') {
    header("Location: admin_login.php");
    exit;
}

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tourism_id = (int)$_POST['tourism_id'];

    if ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM tourism WHERE id = ?");
        $stmt->execute([$tourism_id]);
    }

    header("Location: manage_tourism.php");
    exit;
}

// Fetch all tourism places
$stmt = $pdo->query("SELECT * FROM tourism");
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Tourism - Admin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
body { font-family:"Segoe UI", Arial, sans-serif; background:#f4f7fb; margin:0; }
.container { max-width:1200px; margin:30px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#4a90e2; margin-bottom:20px; }
.add-btn { display:inline-block; margin-bottom:15px; padding:8px 15px; background:#4a90e2; color:white; border-radius:8px; text-decoration:none; font-size:1rem; }
.add-btn:hover { background:#357ab8; }

table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#4a90e2; color:white; }

td img { max-width:80px; height:auto; border-radius:4px; vertical-align:middle; }

.action-btn { padding:6px 10px; border:none; border-radius:6px; margin:2px; color:white; text-decoration:none; display:inline-block; font-size:0.9rem; line-height:1.2; }
.edit { background:#4a90e2; }
.delete { background:red; }
.action-btn:hover { opacity:0.9; }

@media (max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    th { display:none; }
    td { padding:10px; border:none; border-bottom:1px solid #eee; text-align:left; position:relative; padding-left:140px; }
    td::before { content: attr(data-label); font-weight:bold; display:block; position:absolute; left:10px; width:120px; text-align:left; top: 10px; }
    td img { max-width:100px; height:auto; }
}
</style>
</head>
<body>

<div class="container">
    <h2>Manage Tourism Places</h2>
    <a href="add_tourism.php" class="add-btn"><i class="fas fa-plus"></i> Add Place</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>City</th>
                <th>Place Name</th>
                <th>Category</th>
                <th>Population</th>
                <th>Area</th>
                <th>Image</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($places as $p): ?>
            <tr>
                <td data-label="ID"><?= $p['id'] ?></td>
                <td data-label="City"><?= htmlspecialchars($p['city']) ?></td>
                <td data-label="Place Name"><?= htmlspecialchars($p['place_name']) ?></td>
                <td data-label="Category"><?= htmlspecialchars($p['category']) ?></td>
                <td data-label="Population"><?= number_format($p['population']) ?></td>
                <td data-label="Area"><?= htmlspecialchars($p['area']) ?></td>
                <td data-label="Image"><img src="<?= $p['image'] ?: 'images/places/default.jpg' ?>" alt="Image"></td>
                <td data-label="Description"><?= htmlspecialchars(substr($p['description'],0,50)) ?>...</td>
                <td data-label="Actions">
                    <a href="edit_tourism.php?id=<?= $p['id'] ?>" class="action-btn edit">Edit</a>

                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="tourism_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="action-btn delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
