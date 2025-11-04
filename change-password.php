<?php
include "db.php";
include "navbar.php";
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}
$message="";
$updateMessage="";
if(isset($_POST['changeBtn'])){
    $oldPw= md5($_POST['oldPw']);
    $newPw=md5($_POST['newPw']);
    $confirmPw=md5($_POST['confirmPw']);
    $username=$_SESSION['login'];
   
     if($newPw !== $confirmPw){
        $message = "New password and confirmation password do not match.";
    } else {
    $sql="Select password from users where password=? AND username=?";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([$oldPw,$username]);
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
   
    if($result && ($result['password']==$oldPw)){
        $sql="UPDATE users SET password=? where username=?";
        $stmt=$pdo->prepare($sql);
        $updatepw=$stmt->execute([$newPw,$username]);
        if($updatepw){
            $updateMessage .= " Password changed successfully.";
        }}
 else{
        $message .= "Old password does not match.";
   } } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password - City Information System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<style>
body { font-family:"Segoe UI", sans-serif; background:#f4f7fb; }
.container { max-width:600px; margin:90px auto; padding:20px; background:rgba(200, 218, 233, 1); border-radius:12px; box-shadow:0 10px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2A5D91; margin-bottom:20px; }
.change-pw { display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-top:20px; }
.change-pw div{ padding:5px 0; }
.change-pw p {
  grid-column: 2; /* force error message to stay under input */
  margin: 2px 0 10px;
}

.label { font-weight:bold; color:black; font-size:17px; }
.changeBtn { display:block; margin:20px auto; padding:10px 20px; background:#2A5D91; color:white; border:none; border-radius:8px; cursor:pointer; text-decoration:none; text-align:center; width:200px; }
.changeBtn:hover { background:#1F456F; }
</style>
</head>
<body>
<div class="container">
    
<p style="text-align:center; color:red; margin:10px;"><?php echo $message; ?></p>

<h2 style="text-align:center; color:green; margin:10px;"><?php echo $updateMessage; ?></h2>
    <h2> <?php echo $_SESSION['login']; ?> </h2>

   <form method="post" onSubmit="return validateForm()">
    <div class="change-pw">
        <div class="label">Enter old password:</div> 
        <input type="password" name="oldPw" id="oldpw">
         <p id="oldPw-err" style="color:red;"></p>
      
        <div class="label">Enter new password:</div> 
        <input type="password" name="newPw" id="newpw"
         title="Must contain at least 1 number,an uppercase and a lowercase letter, and at least 6 or more characters">
         <p id="newPw-err" style="color:red;"></p>

        <div class="label" >Confirm new password:</div>
        <input type="password" name="confirmPw" id="confirmpw"> 
         <p id="confirmPw-err" style="color:red;"></p>
      
        <button class="changeBtn" name="changeBtn" type="submit">Change Password</button>
         </div>
</form>
</div>

<script>
  function validateForm(){
  var oldPw= document.getElementById("oldpw").value.trim();
  var newPw= document.getElementById("newpw").value.trim();
   var confirmPw= document.getElementById("confirmpw").value.trim();

  document.getElementById("oldPw-err").innerHTML = "";
  document.getElementById("newPw-err").innerHTML = "";
  document.getElementById("confirmPw-err").innerHTML = "";

  let valid=true;
  // checking for empty un field

  if(oldPw===""){
    document.getElementById("oldPw-err").innerHTML="Please enter the old password";
    valid=false;
  }

  // password validation
  const pwPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;

  if(newPw===""){
    document.getElementById("newPw-err").innerHTML="Please enter new password";
    valid=false;
  }
  else if (!pwPattern.test(newPw)) {
    document.getElementById("newPw-err").innerHTML="Password must contain at least one number, one uppercase, one lowercase letter, and be 6 or more characters long.";
    valid=false;
  }
// for pw confirmation
 if(confirmPw===""){
    document.getElementById("confirmPw-err").innerHTML="Please enter this field for password confirmation";
    valid=false;
  }
  else if (newPw !== confirmPw) {
   document.getElementById("confirmPw-err").innerHTML="New password do not match with confirmation password!";
    valid= false;
  }
      return valid;
    }
  </script>
</body>
</html>