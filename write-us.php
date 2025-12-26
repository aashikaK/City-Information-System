<?php
include "navbar.php";
include "db.php";   

if(!isset($_SESSION['login']) || $_SESSION['login'] == ''){
    header("Location: signin.php");
    exit;
}


$success = "";
$error = "";

if(isset($_POST['submit'])){
    $user_id  = $_SESSION['user_id'];
    $username = $_SESSION['login'];
    $email    = trim($_POST['email']);
    $subject  = trim($_POST['subject']);
    $message  = trim($_POST['message']);

    if($email == "" || $subject == "" || $message == ""){
        $error = "All fields are required!";
    } else {
$sql = "INSERT INTO write_us
        (user_id, username, email, subject, message)
        VALUES (?, ?, ?, ?, ?)";

$query = $dbh->prepare($sql);

$query->execute([
    $user_id,
    $username,
    $email,
    $subject,
    $message
]);

        if($query->execute()){
            $success = "Your message has been sent successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Write Us - City Information System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:"Segoe UI", Arial, sans-serif; }
body { background:#f4f7fb; }

.write-container {
    max-width:700px;
    margin:40px auto;
    padding:0 20px;
}

.write-card {
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.write-card h2 {
    text-align:center;
    margin-bottom:20px;
    color:#4a90e2;
}

.alert-success {
    background:#d4edda;
    color:#155724;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
}

.alert-error {
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
}

.form-group { margin-bottom:15px; }
.form-group label { display:block; margin-bottom:5px; }

.form-group input,
.form-group textarea {
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.form-group textarea {
    height:120px;
    resize:none;
}

.submit-btn {
    width:100%;
    background:#4a90e2;
    color:white;
    border:none;
    padding:12px;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

.submit-btn:hover {
    background:#3a78c2;
}
</style>
</head>

<body>

<div class="write-container">
    <div class="write-card">
        <h2><i class="fas fa-pen"></i> Write Us</h2>

        <?php if($success){ ?>
            <div class="alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if($error){ ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="post">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" value="<?php echo htmlentities($_SESSION['login']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" required></textarea>
            </div>

            <button type="submit" name="submit" class="submit-btn">
                Submit Message
            </button>
        </form>
    </div>
</div>

</body>
</html>
