<?php
$error_message="";
$success_message="";
require "db.php";
session_start();

if(isset($_POST['create'])){
     $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password=md5($_POST['password']);
    $confirm_pw=md5($_POST['confirm_password']);

    // pw and confirm pw check
    if ($password !== $confirm_pw) {
    $error_message = "Passwords do not match!";
}

else{
    // check if un exists
    $sqlun="select username from users where username=?";
    $stmt=$pdo->prepare($sqlun);
    $stmt->execute([$username]);
    $result_uncheck=$stmt->fetch(PDO::FETCH_ASSOC);  //fetch only for one row fetching

    // check if email exists
    $sqlemail="select email from users where email=?";
    $stmt=$pdo->prepare($sqlemail);
    $stmt->execute([$email]);
    $result_emailcheck=$stmt->fetch(PDO::FETCH_ASSOC);

    // errormessage or registration
    if($result_uncheck){
        $error_message= "This username is already in use.Try another";
    }
    else if($result_emailcheck){
        $error_message= "This email is already in use.Try another";
    }
       else{
         $insertsql="INSERT INTO users(username,email,password,created_at) VALUES(?,?,?,NOW())";
        $stmt=$pdo->prepare($insertsql);
        $stmt->execute([$username,$email,$password]);
        $success_message="Signup Successful";
    } }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}
body {
  background: linear-gradient(135deg, #6bb9f0, #4a90e2);
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-container {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 40px 30px;
  width: 380px;
  color: white;
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
  text-align: center;
}

.form-container h2 {
  margin-bottom: 20px;
  font-size: 1.8rem;
  letter-spacing: 1px;
}

.input-group {
  margin-bottom: 15px;
  text-align: left;
}

.input-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
}

.input-group input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  border: none;
  outline: none;
  background: rgba(255,255,255,0.8);
  font-size: 14px;
}

.btn {
  width: 100%;
  padding: 10px;
  background: #ffe082;
  color: #333;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.btn:hover {
  background: #ffd54f;
}

.bottom-text {
  margin-top: 15px;
  font-size: 0.9rem;
}

.bottom-text a {
  color: #ffe082;
  text-decoration: none;
  font-weight: 600;
}

.bottom-text a:hover {
  text-decoration: underline;
}
</style>
</head>
<body>

<div class="form-container">
  <h2><i class="fas fa-user-plus"></i> Sign Up</h2>
<!-- to display error or succes message -->
  <?php if($error_message != "") { ?>
   <div style="color: #ff6b6b; margin-bottom: 10px; font-weight:bold;"><?php echo $error_message; ?></div>
<?php } ?>

<?php if($success_message != "") { ?>
   <div style="color: #00ff99; margin-bottom: 10px;font-weight:bold;"><?php echo $success_message; ?></div>
<?php } ?>

  <form method="POST" onSubmit="return validateForm()" novalidate>
    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" placeholder="Enter your username" required>
        <p id="un-err-msg" style="color:#FFD700;"></p>
    </div>

    <div class="input-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <p id="email-err-msg" style="color:#FFD700;"></p>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Create a password" 
      title="Must contain at least 1 number,an uppercase and a lowercase letter, and at least 6 or more characters"
      required>
        <p id="pw-err-msg" style="color:#FFD700;"></p>
    </div>

    <div class="input-group">
      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter your password" required>
        <p id="c_pw-err-msg" style="color:#FFD700;"></p>
    </div>

    <button type="submit" name="create" class="btn">Create Account</button>
  </form>

  <div class="bottom-text">
    <p>Already have an account? <a href="signin.php">Sign In</a></p>
  </div>
</div>

<script>
  function validateForm(){
  var un= document.getElementById("username").value.trim();
  var pw= document.getElementById("password").value.trim();
  var email= document.getElementById("email").value.trim();
  var c_pw= document.getElementById("confirm_password").value.trim();

  document.getElementById("un-err-msg").innerHTML = "";
  document.getElementById("email-err-msg").innerHTML = "";
  document.getElementById("pw-err-msg").innerHTML = "";
  document.getElementById("c_pw-err-msg").innerHTML = "";

  let valid=true;
  // checking for empty fields

  if(un===""){
    document.getElementById("un-err-msg").innerHTML="Please enter username";
    valid=false;
  }
   if(pw===""){
    document.getElementById("pw-err-msg").innerHTML="Please enter password";
    valid=false;
  }
   if(email===""){
    document.getElementById("email-err-msg").innerHTML="Please enter email";
    valid=false;
  }
  if(c_pw===""){
    document.getElementById("c_pw-err-msg").innerHTML="Please enter this field for password confirmation";
    valid=false;
  }

  // password validation
  const pwPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;

  if (!pwPattern.test(pw)) {
    document.getElementById("pw-err-msg").innerHTML="Password must contain at least one number, one uppercase, one lowercase letter, and be 6 or more characters long.";
    valid=false;
  }

  if (pw !== c_pw) {
   document.getElementById("c_pw-err-msg").innerHTML="Passwords do not match!";
    valid= false;
  }

  // email validation

  if (email.startsWith("@")) {
        document.getElementById("email-err-msg").innerHTML= "Email cannot start with '@'.";
        valid = false;
       }

        else {
    
           // regex pattern explanation:
          // ^[^\s@]+  → string must start with one or more characters that are NOT space or @
          // @         → then must have exactly one '@'
          // [^\s@]+   → then one or more characters (not space or @) for the domain
          // \.        → must have a dot
          // [^\s@]+$  → then one or more characters (not space or @) until the end (like .com, .org, etc.)
          
          let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if(!emailPattern.test(email)) {
          document.getElementById("email-err-msg").innerHTML= "Enter a valid email address.";
          valid = false;
      } }
      return valid;
    }
  </script>
</body>
</html>

