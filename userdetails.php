<?php
include "navbar.php";
include "db.php";

// Redirect if not logged in
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}

// Get user basic info
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

// Handle form submission
$success = $error = "";
if(isset($_POST['update_preferences'])){
    $travel_places = $_POST['travel_places'];
    $stay_type = $_POST['stay_type'];
    $purpose = $_POST['purpose'];
    $travel_season = $_POST['travel_season'];
    $budget_range = $_POST['budget_range'];
    $travel_mode = $_POST['travel_mode'];
    $group_type = $_POST['group_type'];
    $activities = $_POST['activities'];

    try{
        if($preferences){
            $stmt4 = $pdo->prepare("UPDATE user_preferences SET travel_places=?, stay_type=?, purpose=?, travel_season=?, budget_range=?, travel_mode=?, group_type=?, activities=? WHERE user_id=?");
            $stmt4->execute([$travel_places,$stay_type,$purpose,$travel_season,$budget_range,$travel_mode,$group_type,$activities,$user['id']]);
        } else {
            $stmt4 = $pdo->prepare("INSERT INTO user_preferences (user_id, travel_places, stay_type, purpose, travel_season, budget_range, travel_mode, group_type, activities) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt4->execute([$user['id'],$travel_places,$stay_type,$purpose,$travel_season,$budget_range,$travel_mode,$group_type,$activities]);
        }
        $success = "Preferences updated successfully!";
        // refresh
        $stmt3->execute([$user['id']]);
        $preferences = $stmt3->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
        $error = "Error updating preferences: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Preferences - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }
.container { max-width:800px; margin:30px auto; padding:20px; background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
h2 { margin-bottom:20px; color:#4a90e2; text-align:center; }
form { display:flex; flex-direction:column; gap:15px; }
label { font-weight:500; }
input, select, textarea { padding:10px; border-radius:8px; border:1px solid #ccc; }
textarea { resize:none; }
button { padding:10px; border:none; border-radius:8px; background:#4a90e2; color:white; cursor:pointer; transition:0.3s; }
button:hover { background:#357ABD; }
.success { color:green; font-weight:bold; }
.error { color:red; font-weight:bold; }
.profile-pic { width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:10px; }
</style>
</head>
<body>

<div class="container">
    <h2>My Preferences</h2>

    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <div style="text-align:center;">
        <?php if($profile && $profile['profile_pic']) { ?>
            <img src="<?php echo $profile['profile_pic']; ?>" class="profile-pic" alt="Profile Picture">
        <?php } else { ?>
            <img src="images/user_profiles/default.jpg" class="profile-pic" alt="Profile Picture">
        <?php } ?>
    </div>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" value="<?php echo $profile['full_name'] ?? $user['username']; ?>" disabled>

        <label>Email</label>
        <input type="email" value="<?php echo $user['email']; ?>" disabled>

        <label>Travel Places (comma separated)</label>
        <textarea name="travel_places"><?php echo $preferences['travel_places'] ?? ''; ?></textarea>

        <label>Stay Type</label>
        <input type="text" name="stay_type" value="<?php echo $preferences['stay_type'] ?? ''; ?>">

        <label>Purpose</label>
        <input type="text" name="purpose" value="<?php echo $preferences['purpose'] ?? ''; ?>">

        <label>Travel Season</label>
        <input type="text" name="travel_season" value="<?php echo $preferences['travel_season'] ?? ''; ?>">

        <label>Budget Range</label>
        <input type="text" name="budget_range" value="<?php echo $preferences['budget_range'] ?? ''; ?>">

        <label>Travel Mode</label>
        <input type="text" name="travel_mode" value="<?php echo $preferences['travel_mode'] ?? ''; ?>">

        <label>Group Type</label>
        <input type="text" name="group_type" value="<?php echo $preferences['group_type'] ?? ''; ?>">

        <label>Activities / Interests</label>
        <textarea name="activities"><?php echo $preferences['activities'] ?? ''; ?></textarea>

        <button type="submit" name="update_preferences">Save Preferences</button>
    </form>
</div>

</body>
</html>
