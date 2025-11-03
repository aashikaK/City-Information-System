<?php
include "db.php";
include "navbar.php";
if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}
$message="";
if(isset($_POST['changeBtn'])){
    $oldPw= md5($_POST['oldPw']);
    $newPw=md5($_POST['newPw']);
    $confirmPw=md5($_POST['confirmPw']);
    $username=$_SESSION['login'];

     if($oldPw==="" && $newPw==="" && $confirmPw===""){
        $message="Please fill all the fields.";
        exit;
     }

    $sql="Select password from users where password=? AND username=?";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([$oldPw,$username]);
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
   
    if($result && ($newPw== $confirmPw)){
        $sql="UPDATE users SET password=? where username=?";
        $stmt=$pdo->prepare($sql);
        $updatepw=$stmt->execute([$newPw,$username]);

        if($updatepw){
            $message .= " Password changed successfully.";
        }
        else{
            $message .= " New passwords do not match with confirmation password.";
        }
    }
 else{
        $message .= " Old password do not match.";
    }
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
.container { max-width:600px; margin:90px auto; padding:20px; background:rgba(200, 218, 233, 1); border-radius:12px; box-shadow:0 10px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#2A5D91; margin-bottom:20px; }
.change-pw { display:grid; grid-template-columns:1fr 2fr; gap:10px; margin-top:20px; }
.change-pw div{ padding:5px 0; }
.label { font-weight:bold; color:black; font-size:17px; }
.changeBtn { display:block; margin:20px auto; padding:10px 20px; background:#2A5D91; color:white; border:none; border-radius:8px; cursor:pointer; text-decoration:none; text-align:center; width:200px; }
.changeBtn:hover { background:#1F456F; }
</style>
</head>
<body>
<p style="text-align:center; color:red;"><?php echo $message; ?></p>
<div class="container">
    <h2> <?php echo $_SESSION['login']; ?> </h2>
   <form method="post">
    <div class="change-pw">
        <div class="label">Enter old password:</div> 
        <input type="password" name="oldPw">
        <div class="label">Enter new password:</div> 
        <input type="password" name="newPw" >
        <div class="label" >Confirm new password:</div>
        <input type="password" name="confirmPw"> 
        <button class="changeBtn" type="submit">Change Password</button>
         </div>
<form>
</div>

</body>
</html>