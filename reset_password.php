<?php
require "db.php";
session_start();

$error_message = "";
$success_message = "";

// Protect page (direct access not allowed)
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit;
}

$email = $_SESSION['reset_email'];

if (isset($_POST['reset'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == "" || $confirm_password == "") {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // same hashing as your system (MD5)
        $hashed_pw = md5($password);

        $sql = "UPDATE users SET password=? WHERE email=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_pw, $email]);

        // Clear reset session
        unset($_SESSION['reset_email']);

        $success_message = "Password reset successful.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:"Segoe UI", Arial, sans-serif;
}
body{
  background: linear-gradient(135deg,#0766a9ff,#032c5cff);
  height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
}
.form-container{
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  border-radius:15px;
  padding:40px 30px;
  width:380px;
  color:white;
  text-align:center;
  box-shadow:0 8px 20px rgba(0,0,0,0.3);
}
.form-container h2{
  margin-bottom:20px;
}
.input-group{
  margin-bottom:15px;
  text-align:left;
}
.input-group label{
  display:block;
  margin-bottom:5px;
}
.input-group input{
  width:100%;
  padding:10px;
  border-radius:8px;
  border:none;
  outline:none;
}
.btn{
  width:100%;
  padding:10px;
  background:#ffe082;
  border:none;
  border-radius:8px;
  font-weight:600;
  cursor:pointer;
}
.btn:hover{
  background:#ffd54f;
}
</style>
</head>

<body>

<div class="form-container">
  <h2><i class="fas fa-key"></i> Reset Password</h2>

  <?php if($error_message!=""){ ?>
    <div style="color:#ffd54f;margin-bottom:10px;font-weight:bold;">
      <?php echo $error_message; ?>
    </div>
  <?php } ?>

  <?php if($success_message!=""){ ?>
    <div style="color:#00ff99;margin-bottom:10px;font-weight:bold;">
      <?php echo $success_message; ?>
    </div>

    <a href="signin.php" class="btn" style="display:block;text-decoration:none;text-align:center;">
      Go to Login
    </a>

  <?php } else { ?>

  <form method="POST">
    <div class="input-group">
      <label>New Password</label>
      <input type="password" name="password" placeholder="Enter new password">
    </div>

    <div class="input-group">
      <label>Confirm Password</label>
      <input type="password" name="confirm_password" placeholder="Confirm new password">
    </div>

    <button type="submit" name="reset" class="btn">Reset Password</button>
  </form>

  <?php } ?>
</div>

</body>
</html>