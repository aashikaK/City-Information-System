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

// Handle form submission
$success = $error = "";
if(isset($_POST['update_profile'])){
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $age = $_POST['age'];

    // Handle profile picture upload
    $profile_pic_path = $profile['profile_pic']; // keep old if not changed
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != ''){
        $target_dir = "images/user_profiles/";
        $filename = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $filename;

        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)){
            $profile_pic_path = $target_file;
        } else {
            $error = "Failed to upload picture.";
        }
    }

    if(!$error){
        // Update or insert profile
        if($profile){
            $stmt3 = $pdo->prepare("UPDATE user_profiles SET full_name=?, phone=?, address=?, gender=?, nationality=?, age=?, profile_pic=? WHERE user_id=?");
            $stmt3->execute([$full_name,$phone,$address,$gender,$nationality,$age,$profile_pic_path,$user['id']]);
        } else {
            $stmt3 = $pdo->prepare("INSERT INTO user_profiles (user_id, full_name, phone, address, gender, nationality, age, profile_pic) VALUES (?,?,?,?,?,?,?,?)");
            $stmt3->execute([$user['id'],$full_name,$phone,$address,$gender,$nationality,$age,$profile_pic_path]);
        }
        $success = "Profile updated successfully!";
        // refresh profile info
        $stmt2->execute([$user['id']]);
        $profile = $stmt2->fetch(PDO::FETCH_ASSOC);
    }
}

// Handle deactivate account
if(isset($_POST['deactivate'])){
    $stmt4 = $pdo->prepare("UPDATE users SET status='inactive' WHERE id=?");
    $stmt4->execute([$user['id']]);
    session_destroy();
    header("Location: signin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }
.profile-container { max-width:800px; margin:30px auto; padding:20px; background:white; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
h2 { margin-bottom:20px; color:#4a90e2; text-align:center; }
form { display:flex; flex-direction:column; gap:15px; }
label { font-weight:500; }
input, select { padding:10px; border-radius:8px; border:1px solid #ccc; }
button { padding:10px; border:none; border-radius:8px; background:#4a90e2; color:white; cursor:pointer; transition:0.3s; }
button:hover { background:#357ABD; }
.success { color:green; font-weight:bold; }
.error { color:red; font-weight:bold; }
.profile-pic { width:220px; height:220px; object-fit:cover; border-radius:50%; margin-bottom:10px; }
</style>
</head>
<body>


<div class="profile-container">
    <h2>My Profile</h2>

    <?php if($success) { echo "<div class='success'>$success</div>"; } ?>
    <?php if($error) { echo "<div class='error'>$error</div>"; } ?>

    <form method="POST" enctype="multipart/form-data">
        <div style="text-align:center;">
           <?php if($profile && $profile['profile_pic']) { ?>
    <img src="<?php echo $profile['profile_pic']; ?>" alt="Profile Picture" class="profile-pic">
<?php } else { ?>
    <?php if($profile && ($profile['gender'] == "Male" || $profile['gender'] == "Other")) { ?>
        <img src="images/user_profiles/male.jpg" alt="Profile Picture" class="profile-pic">
    <?php } else { ?>
        <img src="images/user_profiles/female.jpg" alt="Profile Picture" class="profile-pic">
    <?php } ?>
<?php } ?>

        </div>

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo $profile['full_name'] ?? ''; ?>">

        <label>Email (cannot change)</label>
        <input type="email" value="<?php echo $user['email']; ?>" disabled>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $profile['phone'] ?? ''; ?>">

        <label>Address</label>
        <input type="text" name="address" value="<?php echo $profile['address'] ?? ''; ?>">

        <label>Gender</label>
        <select name="gender">
            <option value="Male" <?php if(($profile['gender']??'')=='Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if(($profile['gender']??'')=='Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if(($profile['gender']??'')=='Other') echo 'selected'; ?>>Other</option>
        </select>

        <label>Nationality</label>
        <input type="text" name="nationality" value="<?php echo $profile['nationality'] ?? ''; ?>">

        <label>Age</label>
        <input type="number" name="age" value="<?php echo $profile['age'] ?? ''; ?>">

        <label>Change Profile Picture</label>
        <input type="file" name="profile_pic" accept="image/*">

        <button type="submit" name="update_profile">Update Profile</button>
    </form>

    <form method="POST" style="margin-top:15px;">
        <button type="submit" name="deactivate" style="background:red;">Deactivate Account</button>
    </form>
</div>

</body>
</html>
