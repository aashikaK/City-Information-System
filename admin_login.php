<?php
$error_message="";
require "db.php";
session_start();

if(isset($_POST['login'])){
    $username = trim($_POST['username']);
    $password=$_POST['password'];

    // check if un exists
    $sql="select username,password from users where username=? AND password=?";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([$username,$password]);
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);  //fetch only for one row fetching

    if(!$result){
        $error_message= "Either username or password do not match. Please Enter correct username and password";
        header("Location:admin_login.php");
    }
       else{
        $_SESSION['username']=$username;
       header("Location:adminpanel.php");
    } 
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
  background: linear-gradient(135deg, #0766a9ff, #032c5cff);
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

</style>
</head>
<body>

<div class="form-container">
  <h2><i class="fas fa-user"></i>Admin Login</h2>
<!-- to display error message -->
  <?php if($error_message != "") { ?>
   <div style="color: #ff6b6b; margin-bottom: 10px; font-weight:bold;"><?php echo $error_message; ?></div>
<?php } ?>

  <form method="POST" onSubmit="return validateForm()" novalidate>
    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" placeholder="Enter your username" required>
        <p id="un-err-msg" style="color: #ff6b6b;"></p>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Create a password" 
      title="Must contain at least 1 number,an uppercase and a lowercase letter, and at least 6 or more characters"
      required>
        <p id="pw-err-msg" style="color: #ff6b6b;;"></p>
    </div>

    <button type="submit" name="login" class="btn">Login</button>
  </form>

</div>

<script>
  function validateForm(){
  var un= document.getElementById("username").value.trim();
  var pw= document.getElementById("password").value.trim();

  document.getElementById("un-err-msg").innerHTML = "";
  document.getElementById("pw-err-msg").innerHTML = "";

  let valid=true;

  // checking for empty un field

  if(un===""){
    document.getElementById("un-err-msg").innerHTML="Please enter username";
    valid=false;
  }

  // password validation

  if(pw===""){
    document.getElementById("pw-err-msg").innerHTML="Please enter password";
    valid=false;
  }
      return valid;
    }
  </script>
</body>
</html>

