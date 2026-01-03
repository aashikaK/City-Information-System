<?php
require "db.php";
include "navbar.php";


// Make sure user is logged in
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}
$username = $_SESSION['login'];

// Get user_id from username
$user_stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$user_stmt->execute([':username'=>$username]);
$user_id = $user_stmt->fetchColumn();



?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Event History</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }
.page-header { background:#3F84B1; color:white; padding:40px 20px; text-align:center; }
.page-header h1 { font-size:2.5rem; }
.events-container { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:20px; width:90%; margin:20px auto; justify-items:center; }
.event-card { background:rgba(200, 218, 233, 1); padding:15px; border-radius:10px; box-shadow:0 4px 8px rgba(0, 0, 0, 0.42); transition:0.3s;max-width:360px;
    width:100%; }
.event-card:hover { transform: translateY(-5px); box-shadow:0 6px 12px rgba(0,0,0,0.2); }
.event-card strong { font-size:1.5rem; color:#222; }
.event-date { color:#555; font-size:0.95rem; }
.event-location { font-style:italic; color:#777; font-size:0.9rem; }
.popular-badge { display:inline-block; background:#ffe082; color:#333; font-weight:bold; padding:3px 6px; border-radius:5px; margin-left:5px; font-size:0.85rem; }
.event-image { width:100%; height:180px; object-fit:cover; border-radius:10px; margin-bottom:10px; }
.register-btn, .save-btn { background:#3F84B1; color:white; padding:6px 12px; border:none; border-radius:5px; cursor:pointer; margin-top:10px; }
.register-btn:hover, .save-btn:hover { background:#4a90e2; }
.registered, .cancelled { background:#555; color:white; padding:8px 12px; border-radius:5px; margin-top:10px; display:inline-block; }
textarea, input[type=number] { width:100%; padding:6px; margin-top:5px; border-radius:5px; border:1px solid #ccc; resize:none; }
.footer { background:#3F84B1; color:white; text-align:center; padding:15px 20px; margin-top:30px; }

[data-aos] { opacity: 1 !important; transform: none !important; }

@media(max-width:768px){ 
.events-container { 
grid-template-columns:1fr;
 } 
 }
</style>
</head>
<body>

<div class="page-header" data-aos="fade-down">
    <h1>My Booking History</h1>
</div>

<div class="events-container">
<?php
if ($events) {
    $delay = 0;
    foreach ($events as $event) {
        echo "<div class='event-card' data-aos='fade-up' data-aos-delay='{$delay}'>";
        
        // Event image
        $img_path = !empty($event['image_path']) ? htmlspecialchars($event['image_path']) : 'images/events/default.jpg';
        echo "<img src='{$img_path}' class='event-image' alt='Event Image'>";

        // Name & popular badge
        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong>";
        if (!empty($event['is_popular'])) echo "<span class='popular-badge'>Popular</span>";

        // Date & location
        echo "<div class='event-date'>" . date("d M Y", strtotime($event['event_date'])) . "</div>";
        echo "<div class='event-location'>📍 " . htmlspecialchars($event['city']) . " - " . htmlspecialchars($event['location']) . "</div>";

        // Description
        echo "<p>" . htmlspecialchars($event['description']) . "</p>";

        // Status and actions
        if ($event['status'] == 'registered' && strtotime($event['event_date']) >= time()) {
            echo "<div class='registered'>Upcoming</div>";
            echo "<form method='POST' action='cancel_event.php'>";
            echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
            echo "<button type='submit' class='register-btn'>Cancel Registration</button>";
            echo "</form>";
        } elseif ($event['status'] == 'attended' || strtotime($event['event_date']) < time()) {
            echo "<div class='registered'>Completed</div>";
            echo "<form method='POST' action='update_event_feedback.php'>";
            echo "<label>Rating (1-5):</label>";
            $rating = $event['rating'] ?? '';
            echo "<input type='number' name='rating' min='1' max='5' value='{$rating}'>";
            echo "<label>Review:</label>";
            $review = htmlspecialchars($event['review'] ?? '');
            echo "<textarea name='review'>{$review}</textarea>";
            echo "<label>Personal Note:</label>";
            $note = htmlspecialchars($event['personal_note'] ?? '');
            echo "<textarea name='personal_note'>{$note}</textarea>";
            echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
            echo "<button type='submit' name='save_btn' class='save-btn'>Save</button>";
            echo "</form>";
        } elseif ($event['status'] == 'cancelled') {
            echo "<div class='cancelled'>Cancelled</div>";
            echo "<form method='POST' action='register_event.php'>";
            echo "<input type='hidden' name='event_id' value='{$event['event_id']}'>";
            echo "<button type='submit' class='register-btn'>Re-register</button>";
            echo "</form>";
        }

        echo "</div>";
        $delay += 100;
    }
} else {
    echo "<p style='text-align:center; color:#777;'>You have no event history yet.</p>";
}
?>
</div>

<div class="footer">
    &copy; 2025 City Information System. All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration:800, once:true });
  window.addEventListener('load', AOS.refresh);
</script>

</body>
</html>
