<?php
require "db.php";
session_start();

$error_message = "";
$success_message = "";

if(isset($_POST['submit'])){
    $email = trim($_POST['email']);

    if($email == ""){
        $error_message = "Please enter your email.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            $error_message = "No account found with this email.";
        } else {
            // For next step (OTP / reset link)
            $_SESSION['reset_email'] = $email;
            $success_message = "Email verified. You can reset your password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>

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
.link-text{
  margin-top:15px;
  font-size:14px;
}
.link-text a{
  color:#ffe082;
  text-decoration:none;
  font-weight:bold;
}
.link-text a:hover{
  text-decoration:underline;
}
</style>
</head>

<body>

<div class="form-container">
  <h2><i class="fas fa-unlock-alt"></i> Forgot Password</h2>

  <?php if($error_message!=""){ ?>
    <div style="color:#ffd54f;margin-bottom:10px;font-weight:bold;">
      <?php echo $error_message; ?>
    </div>
  <?php } ?>

  <?php if($success_message!=""){ ?>
    <div style="color:#00ff99;margin-bottom:10px;font-weight:bold;">
      <?php echo $success_message; ?>
    </div>

    <!-- NEXT STEP BUTTON -->
    <a href="reset_password.php" class="btn" style="display:block;text-decoration:none;text-align:center;">
      Continue
    </a>

  <?php } else { ?>

  <form method="POST">
    <div class="input-group">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your registered email">
    </div>

    <button type="submit" name="submit" class="btn">Verify Email</button>
  </form>

  <?php } ?>

  <div class="link-text">
    <a href="signin.php">Back to Sign In</a>
  </div>
</div>

</body>
</html>