<?php
require "db.php";

// Optional: get filter/sort from GET params
$filter_city = isset($_GET['city']) ? $_GET['city'] : '';
$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'DESC' : 'ASC';

try {
    $sql = "SELECT * FROM events WHERE event_date >= CURDATE()";
    $params = [];

    if ($filter_city != '') {
        $sql .= " AND city = :city";
        $params[':city'] = $filter_city;
    }

    $sql .= " ORDER BY event_date $sort_order";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // For filter dropdown, fetch distinct cities
    $city_stmt = $pdo->query("SELECT DISTINCT city FROM events ORDER BY city ASC");
    $cities = $city_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Events - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<!-- AOS Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Navbar reuse - assume navbar.php included */

/* Page Header */
.page-header {
    background:#3F84B1;
    color:white;
    padding:40px 20px;
    text-align:center;
}
.page-header h1 { font-size:2.5rem; }

/* Filter & Sort */
.filter-sort {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    margin:20px 10px;
    gap:15px;
}
.filter-sort select, .filter-sort button {
    padding:8px 12px;
    font-size:1rem;
    border-radius:5px;
    border:1px solid #ccc;
}

/* Events Section */
.events-container {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(280px,1fr));
    gap:20px;
    width:90%;
    margin:20px auto;
}
.event-card {
    background:rgba(200, 218, 233, 1);
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 8px rgba(0, 0, 0, 0.42);
    transition:0.3s;
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

/* Footer */
.footer {
    background:#3F84B1;
    color:white;
    text-align:center;
    padding:15px 20px;
    margin-top:30px;
}

/* Responsive */
@media(max-width:768px){
    .filter-sort { flex-direction:column; align-items:center; }
}
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="page-header" data-aos="fade-down">
    <h1>All Upcoming Events</h1>
</div>

<!-- Filter & Sort -->
<div class="filter-sort">
    <form method="GET" action="events.php">
        <select name="city">
            <option value="">All Cities</option>
            <?php foreach($cities as $city): ?>
                <option value="<?php echo htmlspecialchars($city); ?>" <?php if($filter_city==$city) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($city); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="sort">
            <option value="asc" <?php if($sort_order=='ASC') echo 'selected'; ?>>Earliest First</option>
            <option value="desc" <?php if($sort_order=='DESC') echo 'selected'; ?>>Latest First</option>
        </select>
        <button type="submit">Apply</button>
    </form>
</div>

<!-- Events Grid -->
<div class="events-container">
<?php
if ($events) {
    $delay = 0;
    foreach ($events as $event) {
       echo "<div class='event-card' data-aos='fade-up' data-aos-delay='{$delay}'>";
        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong>";
        if ($event['is_popular']) {
            echo "<span class='popular-badge'>Popular</span>";
        }
        echo "<div class='event-date'>" . date("d M Y", strtotime($event['event_date'])) . "</div>";
        echo "<div class='event-location'>📍 " . htmlspecialchars($event['city']) . " - " . htmlspecialchars($event['location']) . "</div>";
        echo "<p>" . htmlspecialchars($event['description']) . "</p>";
        echo "</div>";
        $delay += 100;
    }
} else {
    echo "<p style='text-align:center; color:#777;'>No upcoming events found.</p>";
}
?>
</div>

<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration:800, once:true });
</script>

</body>
</html>
</body>
</html>
