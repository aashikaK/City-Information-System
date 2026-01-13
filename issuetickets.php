<?php
require "db.php";
include('navbar.php'); 

if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$_SESSION['category'] = $selected_category;
$selected_city = isset($_GET['city']) ? $_GET['city'] : 'All Cities';

$categories = [
    'Hospital' => ['icon' => '🏥', 'image' => 'images/categories/hospital.jpg'],
    'Hotel' => ['icon' => '🏨', 'image' => 'images/categories/hotel.jpg']
];

$cities = ['Kathmandu','Bhaktapur','Lalitpur', 'Pokhara','Chitwan','Lumbini','Mustang'];

// Fetch available services based on selection
$services = [];
if ($selected_category) {
    if ($selected_city === 'All Cities' || !$selected_city) {
        // Show all cities
        $stmt = $pdo->prepare("SELECT * FROM city_services WHERE category = ?");
        $stmt->execute([$selected_category]);
    } else {
        // Filter by city
        $stmt = $pdo->prepare("SELECT * FROM city_services WHERE category = ? AND city = ?");
        $stmt->execute([$selected_category, $selected_city]);
    }
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<style>
*{ margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial; }
body{ background:#f4f7fb; }

/* Page header */
.page-header{ background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1{ font-size:2.3rem; }

/* Category section */
.category-container{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
    width:85%;
    margin:40px auto;
    justify-items:center;
}

.category-card{
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 6px 12px rgba(0,0,0,0.15);
    cursor:pointer;
    transition:0.3s;
    text-align:center;
}

.category-card:hover{ transform:translateY(-5px); }

.category-card img{ width:100%; height:230px; object-fit:cover; }

.category-card div{ padding:15px; font-size:1.2rem; }

/* City filter */
.city-filter{ text-align:center; margin:30px; }

.city-filter select{
    padding:10px 15px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:1rem;
}

/* Service list */
.service-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:30px;
    width:85%;
    margin:20px auto;
    justify-items:center;
}

.service-card{
    background:rgba(200, 218, 233, 1);
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 8px rgba(0, 0, 0, 0.42);
    transition:0.3s;
    max-width:360px;
    width:100%;
}

.service-card:hover{
    transform:translateY(-4px);
    box-shadow:0 8px 18px rgba(0,0,0,0.18);
}

.service-card img{ width:100%; height:200px; object-fit:cover; }

/* Info spacing */
.service-card .info{ padding:15px; }

.service-card .info h3{ margin-bottom:8px; }

.service-card .info p{ margin-bottom:6px; line-height:1.5; }

/* Button interaction */
.book-btn{
    background:#3F84B1;
    color:white;
    border:none;
    padding:8px 15px;
    border-radius:5px;
    cursor:pointer;
    transition:0.3s;
}

.book-btn:hover{
    background:#2f6f96;
    transform:translateY(-2px);
    box-shadow:0 5px 10px rgba(0,0,0,0.2);
}

.book-btn:disabled{
    background:#999;
    cursor:not-allowed;
    transform:none;
    box-shadow:none;
}

/* Footer */
.footer{
    background:#3F84B1;
    color:white;
    text-align:center;
    padding:10px;
    margin-top:30px;
}
</style>
</head>
<body>

<div class="page-header"><h1>Ticket Booking</h1></div>

<?php if (!$selected_category): ?>
<!-- Step 1: Choose Category -->
<div class="category-container">
  <?php foreach($categories as $cat => $data): ?>
    <div class="category-card" onclick="window.location='issuetickets.php?category=<?php echo urlencode($cat); ?>'">
        <img src="<?php echo $data['image']; ?>" alt="<?php echo $cat; ?>">
        <div><?php echo $data['icon'].' '.htmlspecialchars($cat); ?></div>
    </div>
  <?php endforeach; ?>
</div>

<?php else: ?>
<!-- Step 2: Choose City -->
<div class="city-filter">
  <form method="GET" action="issuetickets.php">
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
    <select name="city" onchange="this.form.submit()">
      <option value="All Cities" <?php if($selected_city=='All Cities') echo 'selected'; ?>>-- All Cities --</option>
      <?php foreach($cities as $city): ?>
        <option value="<?php echo $city; ?>" <?php if($selected_city==$city) echo 'selected'; ?>>
          <?php echo $city; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if (count($services) > 0): ?>
<h2 style="text-align:center;margin-bottom:15px;">
  Available <?php echo $selected_category; ?>s 
  <?php if($selected_city != 'All Cities') echo "in ".$selected_city; ?>
</h2>

<div class="service-list">
  <?php foreach($services as $s): ?>
    <div class="service-card">
        <img src="<?php echo htmlspecialchars($s['image']); ?>" alt="<?php echo htmlspecialchars($s['name']); ?>">
        <div class="info">
            <h3><?php echo htmlspecialchars($s['name']); ?></h3>
            <p><?php echo htmlspecialchars($s['description']); ?></p>
            <p><b>Contact:</b> <?php echo htmlspecialchars($s['contact_info']); ?></p>
            <p><b>Address:</b> <?php echo htmlspecialchars($s['location']); ?></p>
            <p><b>Available:</b> 
               <?php echo max(0, $s['capacity'] - $s['current_bookings']); ?> /
               <?php echo $s['capacity']; ?>
            </p>
            <form method="POST" action="book_now.php">
              <input type="hidden" name="service_id" value="<?php echo $s['id']; ?>">
              <input type="hidden" name="category" value="<?php echo $selected_category; ?>">
              <button class="book-btn" 
                <?php if($s['current_bookings'] >= $s['capacity']) echo 'disabled'; ?>>
                <?php echo ($s['current_bookings'] >= $s['capacity']) ? 'Full' : 'Book Now'; ?>
              </button>
            </form>
        </div>
    </div>
  <?php endforeach; ?>
</div>
<?php else: ?>
  <p style="text-align:center;width:100%;">No <?php echo $selected_category; ?>s found<?php if($selected_city != 'All Cities') echo " in ".$selected_city; ?>.</p>
<?php endif; ?>

<?php endif; ?>

<div class="footer">&copy; 2025 City Information System</div>
</body>
</html>
