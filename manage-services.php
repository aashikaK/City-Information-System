<?php
include "admin-navbar.php";

// Protect admin pages
if(!isset($_SESSION['admin']) || $_SESSION['admin'] == ''){
    header("Location: admin_login.php");
    exit;
}

require "db.php";

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = (int)$_POST['service_id'];

    if ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM city_services WHERE id = ?");
        $stmt->execute([$service_id]);
    }

    header("Location: manage-services.php");
    exit;
}

// Filters
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$selected_city = isset($_GET['city']) ? $_GET['city'] : '';

// Fetch categories for filter dropdown
$cat_stmt = $pdo->query("SELECT DISTINCT category FROM city_services ORDER BY category ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch cities for filter dropdown
$city_stmt = $pdo->query("SELECT DISTINCT city FROM city_services ORDER BY city ASC");
$cities = $city_stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch services
$sql = "SELECT * FROM city_services WHERE 1";
$params = [];

if($selected_category){
    $sql .= " AND category=:category";
    $params[':category'] = $selected_category;
}

if($selected_city){
    $sql .= " AND city=:city";
    $params[':city'] = $selected_city;
}

$sql .= " ORDER BY category ASC, city ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Services - Admin</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
body { font-family:"Segoe UI", Arial, sans-serif; background:#f4f7fb; margin:0; }
.container { max-width:1200px; margin:30px auto; background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
h2 { text-align:center; color:#4a90e2; margin-bottom:20px; }
.add-btn { display:inline-block; margin-bottom:15px; padding:8px 15px; background:#4a90e2; color:white; border-radius:8px; text-decoration:none; font-size:1rem; }
.add-btn:hover { background:#357ab8; }

.filter-section { margin-bottom:20px; text-align:center; }
.filter-section select { padding:8px 12px; border-radius:5px; border:1px solid #ccc; font-size:1rem; margin:0 5px; }

table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:center; }
th { background:#4a90e2; color:white; }
td img { max-width:80px; height:auto; border-radius:4px; vertical-align:middle; }
.action-btn { padding:6px 10px; border:none; border-radius:6px; margin:2px; color:white; text-decoration:none; display:inline-block; font-size:0.9rem; line-height:1.2; cursor:pointer; }
.edit { background:#4a90e2; }
.delete { background:red; }
.action-btn:hover { opacity:0.9; }

@media (max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    th { display:none; }
    td { padding:10px; border:none; border-bottom:1px solid #eee; text-align:left; position:relative; padding-left:140px; }
    td::before { content: attr(data-label); font-weight:bold; display:block; position:absolute; left:10px; width:130px; text-align:left; top:10px; }
    td img { max-width:100px; height:auto; }
}
</style>
</head>
<body>

<div class="container">
    <h2>Manage City Services</h2>
    <a href="add_service.php" class="add-btn"><i class="fas fa-plus"></i> Add Service</a>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="manage-services.php">
            <select name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $selected_category==$cat?'selected':'' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="city" onchange="this.form.submit()">
                <option value="">All Cities</option>
                <?php foreach($cities as $city): ?>
                    <option value="<?= htmlspecialchars($city) ?>" <?= $selected_city==$city?'selected':'' ?>>
                        <?= htmlspecialchars($city) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>City</th>
                <th>Name</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if($services): ?>
            <?php foreach($services as $s): ?>
            <tr>
                <td data-label="ID"><?= $s['id'] ?></td>
                <td data-label="Category"><?= htmlspecialchars($s['category']) ?></td>
                <td data-label="City"><?= htmlspecialchars($s['city']) ?></td>
                <td data-label="Name"><?= htmlspecialchars($s['name']) ?></td>
                <td data-label="Location"><?= htmlspecialchars($s['location']) ?></td>
                <td data-label="Contact"><?= htmlspecialchars($s['contact_info']) ?></td>
                <td data-label="Image">
                    <img src="<?= $s['image'] ?: 'images/default_service.jpg' ?>" alt="Service Image">
                </td>
                <td data-label="Actions">
                    <a href="edit_service.php?id=<?= $s['id'] ?>" class="action-btn edit">Edit</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="action-btn delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center; color:#777;">No services found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
