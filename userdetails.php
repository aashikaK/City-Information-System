<?php
include "navbar.php";
include "db.php";

if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
$stmt->execute([$_SESSION['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt2 = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id=?");
$stmt2->execute([$user['id']]);
$profile = $stmt2->fetch(PDO::FETCH_ASSOC);

$stmt3 = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id=?");
$stmt3->execute([$user['id']]);
$preferences = $stmt3->fetch(PDO::FETCH_ASSOC);

function showValue($val){
    return !empty($val) ? htmlspecialchars($val) : '---';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Details - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<style>
body { font-family:"Segoe UI", sans-serif; background:#f4f7fb; }
.container { max-width:800px; margin:30px auto; padding:20px; background:rgba(200, 218, 233, 1); border-radius:12px; box-shadow:0 10px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2A5D91; margin-bottom:20px; }
.profile-pic { width:150px; height:150px; border-radius:50%; object-fit:cover; margin-bottom:15px; display:block; margin-left:auto; margin-right:auto; }
.details { display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-top:20px; }
.details div { padding:5px 0; }
.label { font-weight:bold; color:black; }
.update-btn { display:block; margin:20px auto; padding:10px 20px; background:#2A5D91; color:white; border:none; border-radius:8px; cursor:pointer; text-decoration:none; text-align:center; width:200px; }
.update-btn:hover { background:#1F456F; }
</style>
</head>
<body>

<div class="container">
    <h2>User Details</h2>
    <img src="<?php echo $profile['profile_pic'] ?? 'images/user_profiles/default.jpg'; ?>" class="profile-pic" alt="Profile Picture">

    <div class="details">
        <div class="label">Full Name:</div> <div><?php echo showValue($profile['full_name'] ?? $user['username']); ?></div>
        <div class="label">Email:</div> <div><?php echo showValue($user['email']); ?></div>
        <div class="label">Travel Places:</div> <div><?php echo showValue($preferences['travel_places'] ?? ''); ?></div>
        <div class="label">Stay Type:</div> <div><?php echo showValue($preferences['stay_type'] ?? ''); ?></div>
        <div class="label">Purpose:</div> <div><?php echo showValue($preferences['purpose'] ?? ''); ?></div>
        <div class="label">Travel Season:</div> <div><?php echo showValue($preferences['travel_season'] ?? ''); ?></div>
        <div class="label">Budget Range:</div> <div><?php echo showValue($preferences['budget_range'] ?? ''); ?></div>
        <div class="label">Travel Mode:</div> <div><?php echo showValue($preferences['travel_mode'] ?? ''); ?></div>
        <div class="label">Group Type:</div> <div><?php echo showValue($preferences['group_type'] ?? ''); ?></div>
        <div class="label">Activities:</div> <div><?php echo showValue($preferences['activities'] ?? ''); ?></div>
    </div>

    <a href="update_preferences.php" class="update-btn">Update Preferences</a>
</div>

</body>
</html>
