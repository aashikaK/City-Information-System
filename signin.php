<?php
$error_message = "";
require "db.php";
session_start();

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = md5($_POST['password']); // same as your existing system

    $sql = "SELECT username, role 
            FROM users 
            WHERE username = ? AND password = ? AND status = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error_message = "Either username or password do not match.";
    } else {

        if ($user['role'] === 'admin') {
            $_SESSION['admin'] = $user['username'];
            header("Location: adminpanel.php");
            exit;
        } else {
            $_SESSION['login'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - City Information System</title>
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
.signup-link{
  margin-top:15px;
  font-size:14px;
}
.signup-link a{
  color:#ffe082;
  text-decoration:none;
  font-weight:bold;
}
.signup-link a:hover{
  text-decoration:underline;
}
.input-group {
  position: relative;
}

.eye-icon {
  position: absolute;
  right: 12px;
  top: 38px;
  cursor: pointer;
  color: #555;
}

</style>
</head>

<body>

<div class="form-container">
  <h2><i class="fas fa-user"></i> Sign In</h2>

  <!-- Error message -->
  <?php if($error_message!=""){ ?>
    <div style="color:#ffd54f;margin-bottom:10px;font-weight:bold;">
      <?php echo $error_message; ?>
    </div>
  <?php } ?>

  <form method="POST" onsubmit="return validateForm()">
    
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" id="username">
      <p id="un-err-msg" style="color:#ffd54f;"></p>
    </div>

    <div class="input-group">
  <label>Password</label>
  <input type="password" name="password" id="password">
  <i class="fas fa-eye eye-icon toggle-password" data-target="password"></i>
  <p id="pw-err-msg" style="color:#ffd54f;"></p>
</div>


    <button type="submit" name="login" class="btn">Sign In</button>
    
    <div class="signup-link">
  <a href="forgot_password.php">Forgot Password?</a>
</div>

  </form>

  <!-- SIGNUP LINK -->
  <div class="signup-link">
    Don’t have an account?
    <a href="signup.php">Sign up</a>
  </div>

</div>

<script>
function validateForm(){
  let un=document.getElementById("username").value.trim();
  let pw=document.getElementById("password").value.trim();

  document.getElementById("un-err-msg").innerHTML="";
  document.getElementById("pw-err-msg").innerHTML="";
  let valid=true;

  if(un===""){
    document.getElementById("un-err-msg").innerHTML="Please enter username";
    valid=false;
  }
  if(pw===""){
    document.getElementById("pw-err-msg").innerHTML="Please enter password";
    valid=false;
  }
  return valid;
}
</script>
<script src="password-toggle.js"></script>

</body>
</html>
