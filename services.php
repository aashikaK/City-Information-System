<?php
require "db.php";

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$selected_city = isset($_GET['city']) ? $_GET['city'] : '';

// Define categories with icons and images
$categories = [
    'Hospital' => ['icon' => '🏥', 'image' => 'images/categories/hospital.jpg'],
    'School' => ['icon' => '🏫', 'image' => 'images/categories/school.jpg'],
    'University' => ['icon' => '🎓', 'image' => 'images/categories/university.jpg'],
    'College' => ['icon' => '🎓', 'image' => 'images/categories/college.jpg'],
    'Transport' => ['icon' => '🚌', 'image' => 'images/categories/transport.jpg'],
    'Hotel' => ['icon' => '🏨', 'image' => 'images/categories/hotel.jpg'],
    'Government' => ['icon' => '🏢', 'image' => 'images/categories/government.jpg'],
    'Bank' => ['icon' => '💰', 'image' => 'images/categories/bank.jpg'],
    'Fire Station' => ['icon' => '🔥', 'image' => 'images/categories/firestation.jpg'],
    'Tourism' => ['icon' => 'ℹ️', 'image' => 'images/categories/tourism.jpg']
];


// Fetch cities for dropdown
$city_stmt = $pdo->query("SELECT DISTINCT city FROM city_services ORDER BY city ASC");
$cities = $city_stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch services if a category is selected
$services = [];
if ($selected_category) {
    $sql = "SELECT * FROM city_services WHERE status=1 AND category=:category";
    $params = [':category'=>$selected_category];

    if ($selected_city) {
        $sql .= " AND city=:city";
        $params[':city'] = $selected_city;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Services</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
  
<!-- AOS Library CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

/* Page Header */
.page-header {
    background:#3F84B1; color:white; padding:40px 20px; text-align:center;
}
.page-header h1 { font-size:2.5rem; }

/* Categories Grid */
.category-container {
    display:grid; 
    grid-template-columns:repeat(3, 1fr); /* 3 per row */
    gap:30px; 
    width:85%; 
    margin:40px auto;
}

.category-card {
    background:white; 
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 6px 12px rgba(0,0,0,0.15); 
    cursor:pointer;
    font-size:1.5rem; /* bigger text */
    text-align:center; 
    transition:0.3s;
}
.category-card:hover {
    transform:translateY(-5px);
    box-shadow:0 8px 16px rgba(0,0,0,0.25);
}
.category-card img { 
    display:block; 
    width:100%; 
    height:280px; /* bigger image */
    object-fit:cover; 
    border-top-left-radius:10px; 
    border-top-right-radius:10px;
}
.category-card div { 
    padding:15px; 
}

/* Responsive adjustments */
@media(max-width:1024px){
    .category-container { grid-template-columns:repeat(2,1fr); } /* 2 per row on medium screens */
}
@media(max-width:768px){
    .category-container { grid-template-columns:1fr; } /* 1 per row on small screens */
    .category-card { font-size:1.2rem; }
}
/* City Dropdown */
.city-filter {
    text-align:center; margin:20px 0;
}
.city-filter select {
    padding:8px 12px; font-size:1rem; border-radius:5px; border:1px solid #ccc;
}

/* Services Grid */
.services-container {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px; width:90%; margin:20px auto;
}
.service-card {
    background:#e2ebf5; border-radius:10px; overflow:hidden;
    box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:0.3s;
}
.service-card:hover {
    transform:translateY(-5px);
    box-shadow:0 6px 12px rgba(0,0,0,0.25);
}
.service-card img { width:100%; height:200px; object-fit:cover; }
.service-card .content { padding:15px; }
.service-card h3 { margin-bottom:8px; color:#222; }
.service-card p { font-size:0.95rem; color:#555; margin-bottom:5px; }
.service-card .location { font-style:italic; color:#777; font-size:0.85rem; }

/* Footer */
.footer { background:#3F84B1; color:white; text-align:center; padding:15px 20px; margin-top:30px; }

/* Responsive */
@media(max-width:768px){
    .category-card { font-size:1rem; }
    .city-filter select { width:80%; }
}
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="page-header" data-aos="fade-down">
    <h1>City Services</h1>
</div>

<?php if (!$selected_category): ?>
<!-- Default: Show Categories -->
<div class="category-container">
<?php foreach($categories as $cat => $data): ?>
    <div class="category-card" onclick="window.location='services.php?category=<?php echo urlencode($cat); ?>'" data-aos="zoom-in">
        <img src="<?php echo $data['image']; ?>" alt="<?php echo htmlspecialchars($cat); ?>" >
        <div style="padding:10px; font-size:1.2rem;"><?php echo $data['icon'] . ' ' . htmlspecialchars($cat); ?></div>
    </div>
<?php endforeach; ?>
</div>


<?php else: ?>
<!-- Selected Category: Show City Filter + Services -->
<div class="city-filter" data-aos="fade-up">
    <form method="GET" action="services.php">
        <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
        <select name="city" onchange="this.form.submit()">
            <option value="">All Cities</option>
            <?php foreach($cities as $city): ?>
                <option value="<?php echo htmlspecialchars($city); ?>" <?php if($selected_city==$city) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($city); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<h2 style="text-align:center; margin-bottom:20px;" data-aos="fade-up">
    <?php echo $categories[$selected_category]['icon'] . ' ' . htmlspecialchars($selected_category); ?>
</h2>

<div class="services-container">
    <?php
    if ($services) {
        foreach ($services as $service) {
            echo "<div class='service-card' data-aos='fade-up'>";
            echo "<img src='".($service['image'] ? $service['image'] : 'images/default_service.jpg')."' alt='".htmlspecialchars($service['name'])."'>";
            echo "<div class='content'>";
            echo "<h3>".htmlspecialchars($service['name'])."</h3>";
            echo "<p>".htmlspecialchars($service['description'])."</p>";
            echo "<p class='location'>📍 ".htmlspecialchars($service['city'])." - ".htmlspecialchars($service['location'])."</p>";
            echo "<p>📞 ".htmlspecialchars($service['contact_info'])."</p>";
            echo "</div></div>";
        }
    } else {
        echo "<p style='text-align:center; color:#777;'>No services found for this category/city.</p>";
    }
    ?>
</div>
<?php endif; ?>

<div class="footer" data-aos="fade-up">
    &copy; 2025 City Information System. All rights reserved.
</div>

<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration:800, once:true });
</script>

</body>
</html>
