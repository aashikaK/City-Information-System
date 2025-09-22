<?php
require "db.php";

$sql = "SELECT * FROM tourism WHERE city=? ORDER BY city ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(["Pokhara"]);
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tourism - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
body { background:#f4f7fb; font-family:"Segoe UI", Arial, sans-serif; }
.page-header { background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1 { font-size:2.5rem; }
.places-container { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px; width:90%; margin:20px auto; }
.place-card { background:#e2ebf5; border-radius:10px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:0.3s; }
.place-card:hover { transform:translateY(-5px); }
.place-card img { width:100%; height:200px; object-fit:cover; }
.place-card .content { padding:15px; }
.place-card h3 { margin-bottom:10px; }
.place-card p { font-size:0.95rem; margin-bottom:5px; color:#555; }
.footer { background:#3F84B1; color:white; text-align:center; padding:15px; margin-top:30px; }
</style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="page-header" data-aos="fade-down">
  <h1>Tourism Places in Kathmandu</h1>
</div>

<div class="places-container">
<?php
if ($places) {
    foreach ($places as $p) {
        echo "<div class='place-card' data-aos='fade-up'>";
        echo "<img src='".htmlspecialchars($p['image'])."' alt='".htmlspecialchars($p['place_name'])."'>";
        echo "<div class='content'>";
        echo "<h3>".htmlspecialchars($p['place_name'])."</h3>";
        echo "<p><b>City:</b> ".htmlspecialchars($p['city'])."</p>";
        echo "<p><b>Category:</b> ".htmlspecialchars($p['category'])."</p>";
        echo "<p>".htmlspecialchars($p['description'])."</p>";
        echo "<p><b>Population:</b> ".number_format($p['population'])."</p>";
        echo "<p><b>Area:</b> ".htmlspecialchars($p['area'])."</p>";
        echo "</div></div>";
    }
} else {
    echo "<p style='text-align:center; color:#777;'>No tourism places found.</p>";
}
?>
</div>

<div class="footer">&copy; 2025 City Information System. All rights reserved.</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({duration:800,once:true});
    window.addEventListener('load', AOS.refresh);
</script>

</body>
</html>
