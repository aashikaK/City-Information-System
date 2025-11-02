<?php
include "db.php";
include "navbar.php";
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}
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
.container { max-width:800px; margin:30px auto; padding:20px; background:rgba(200, 218, 233, 1); border-radius:12px; box-shadow:0 10px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2A5D91; margin-bottom:20px; }
.change-pw { display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-top:20px; }
.change-pw { padding:5px 0; }
.label { font-weight:bold; color:black; }
.changeBtn { display:block; margin:20px auto; padding:10px 20px; background:#2A5D91; color:white; border:none; border-radius:8px; cursor:pointer; text-decoration:none; text-align:center; width:200px; }
.changeBtn:hover { background:#1F456F; }
</style>
</head>
<body>

<div class="container">
    <h2> <?php echo $_SESSION['login']; ?> </h2>
   
    <div class="change-pw">
        <div class="label" name="oldPw">Enter old password</div> 
        <div class="label" name="newPw">Enter new password</div> 
        <div class="label" name="confirmPw">Confirm new password</div> 
        <button class="changeBtn" type="submit">Change Password</button>
         </div>

</div>

</body>
</html>