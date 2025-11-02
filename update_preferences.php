<?php
ob_start(); // start output buffering
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

// Get user profile info
$stmt2 = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id=?");
$stmt2->execute([$user['id']]);
$profile = $stmt2->fetch(PDO::FETCH_ASSOC);

// Get user preferences
$stmt3 = $pdo->prepare("SELECT * FROM user_preferences WHERE user_id=?");
$stmt3->execute([$user['id']]);
$preferences = $stmt3->fetch(PDO::FETCH_ASSOC);

$success = $error = "";

if(isset($_POST['save_preferences'])){
    $travel_places = $_POST['travel_places'];
    $stay_type = $_POST['stay_type'];
    $purpose = $_POST['purpose'];
    $travel_season = $_POST['travel_season'];
    $budget_range = $_POST['budget_range'];
    $travel_mode = $_POST['travel_mode'];
    $group_type = $_POST['group_type'];
    $activities = $_POST['activities'];

    try {
        if($preferences){
            $stmt4 = $pdo->prepare("UPDATE user_preferences SET travel_places=?, stay_type=?, purpose=?, travel_season=?, budget_range=?, travel_mode=?, group_type=?, activities=? WHERE user_id=?");
            $stmt4->execute([$travel_places,$stay_type,$purpose,$travel_season,$budget_range,$travel_mode,$group_type,$activities,$user['id']]);
        } else {
            $stmt4 = $pdo->prepare("INSERT INTO user_preferences (user_id, travel_places, stay_type, purpose, travel_season, budget_range, travel_mode, group_type, activities) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt4->execute([$user['id'],$travel_places,$stay_type,$purpose,$travel_season,$budget_range,$travel_mode,$group_type,$activities]);
        }

        // Redirect back to user details after saving
        header("Location: userdetails.php");
        exit;

    } catch(PDOException $e){
        $error = "Error saving preferences: " . $e->getMessage();
    }
}
ob_end_flush(); // send output at the end
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Preferences - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<style>
body { font-family:"Segoe UI", sans-serif; background:#f4f7fb; }
.container { max-width:800px; margin:30px auto; padding:20px; background:white; border-radius:12px; box-shadow:0 10px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2A5D91; margin-bottom:20px; }
label { font-weight:bold; margin-top:10px; display:block; }
input, textarea { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; margin-top:5px; }
textarea { resize:none; }
button { display:block; margin:20px auto; padding:10px 20px; background:#2A5D91; color:white; border:none; border-radius:8px; cursor:pointer; }
button:hover { background:#1F456F; }
.success { color:green; text-align:center; }
.error { color:red; text-align:center; }
.profile-pic { width:150px; height:150px; border-radius:50%; object-fit:cover; margin-bottom:15px; display:block; margin-left:auto; margin-right:auto; }
</style>
</head>
<body>

<div class="container">
    <h2>Update Preferences</h2>

    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>

    <div style="text-align:center;">
        <img src="<?php echo $profile['profile_pic'] ?? 'images/user_profiles/default.jpg'; ?>" class="profile-pic" alt="Profile Picture">
    </div>

    <form method="POST">
        <label>Travel Places (comma separated)</label>
        <textarea name="travel_places"><?php echo htmlspecialchars($preferences['travel_places'] ?? ''); ?></textarea>

        <label>Stay Type</label>
        <input type="text" name="stay_type" value="<?php echo htmlspecialchars($preferences['stay_type'] ?? ''); ?>">

        <label>Purpose</label>
        <input type="text" name="purpose" value="<?php echo htmlspecialchars($preferences['purpose'] ?? ''); ?>">

        <label>Travel Season</label>
        <input type="text" name="travel_season" value="<?php echo htmlspecialchars($preferences['travel_season'] ?? ''); ?>">

        <label>Budget Range</label>
        <input type="text" name="budget_range" value="<?php echo htmlspecialchars($preferences['budget_range'] ?? ''); ?>">

        <label>Travel Mode</label>
        <input type="text" name="travel_mode" value="<?php echo htmlspecialchars($preferences['travel_mode'] ?? ''); ?>">

        <label>Group Type</label>
        <input type="text" name="group_type" value="<?php echo htmlspecialchars($preferences['group_type'] ?? ''); ?>">

        <label>Activities / Interests</label>
        <textarea name="activities"><?php echo htmlspecialchars($preferences['activities'] ?? ''); ?></textarea>

        <button type="submit" name="save_preferences">Save Preferences</button>
    </form>
</div>

</body>
</html>
