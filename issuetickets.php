<?php
require "db.php";

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$selected_city = isset($_GET['city']) ? $_GET['city'] : '';

$categories = [
    'Hospital' => ['icon' => '🏥', 'image' => 'images/categories/hospital.jpg'],
    'Hotel' => ['icon' => '🏨', 'image' => 'images/categories/hotel.jpg']
];

// Just sample cities — replace later with DB values
$cities = ['Kathmandu', 'Pokhara', 'Lalitpur', 'Bhaktapur', 'Chitwan', 'Lumbini','Mustang'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:"Segoe UI",Arial;}
body{background:#f4f7fb;}
.page-header{background:#3F84B1;color:white;padding:40px 20px;text-align:center;}
.page-header h1{font-size:2.3rem;}
.category-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:25px;width:85%;margin:40px auto;}
.category-card{background:white;border-radius:10px;overflow:hidden;box-shadow:0 6px 12px rgba(0,0,0,0.15);cursor:pointer;transition:0.3s;text-align:center;}
.category-card:hover{transform:translateY(-5px);}
.category-card img{width:100%;height:230px;object-fit:cover;}
.category-card div{padding:15px;font-size:1.2rem;}
.city-filter{text-align:center;margin:30px;}
.city-filter select{padding:10px 15px;border:1px solid #ccc;border-radius:5px;font-size:1rem;}
.footer{background:#3F84B1;color:white;text-align:center;padding:10px;margin-top:30px;}
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="page-header"><h1>Ticket Booking</h1></div>

<?php if (!$selected_category): ?>
<!-- Step 1: Choose Category -->
<div class="category-container">
  <?php foreach($categories as $cat => $data): ?>
    <div class="category-card" onclick="window.location='booking.php?category=<?php echo urlencode($cat); ?>'">
        <img src="<?php echo $data['image']; ?>" alt="<?php echo $cat; ?>">
        <div><?php echo $data['icon'].' '.htmlspecialchars($cat); ?></div>
    </div>
  <?php endforeach; ?>
</div>

<?php else: ?>
<!-- Step 2: Choose City -->
<div class="city-filter">
  <form method="GET" action="booking.php">
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
    <select name="city" onchange="this.form.submit()">
      <option value="">-- Select City --</option>
      <?php foreach($cities as $city): ?>
        <option value="<?php echo $city; ?>" <?php if($selected_city==$city) echo 'selected'; ?>>
          <?php echo $city; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if ($selected_city): ?>
<p style="text-align:center;font-size:1.2rem;">You selected <b><?php echo $selected_category; ?></b> in <b><?php echo $selected_city; ?></b>.</p>
<p style="text-align:center;color:#555;">(You’ll display available places and booking options here later.)</p>
<?php endif; ?>

<?php endif; ?>

<div class="footer">&copy; 2025 City Information System</div>
</body>
</html>
