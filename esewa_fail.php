<?php
session_start();

// Only allow logged-in users
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

// eSewa usually sends POST data here too, but sometimes it’s empty
$pid   = $_POST['pid'] ?? $_SESSION['pid'] ?? 'Unknown';
$amt   = $_POST['amt'] ?? $_SESSION['amount'] ?? 'Unknown';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .fail-box {
            background: #fff;
            max-width: 420px;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            text-align: center;
        }
        .fail-box h2 {
            color: #d9534f;
            margin-bottom: 20px;
        }
        .fail-box p {
            font-size: 1rem;
            margin: 10px 0;
            color: #333;
        }
        .fail-box a {
            display: inline-block;
            margin-top: 20px;
            background: #3F84B1;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }
        .fail-box a:hover {
            background: #2f6d93;
        }
    </style>
</head>
<body>
    <div class="fail-box">
        <h2>Payment Failed!</h2>
        <p>Booking ID: <?php echo htmlspecialchars($pid); ?></p>
        <p>Amount: Rs. <?php echo htmlspecialchars($amt); ?></p>
        <p>Your payment was not successful. Please try again.</p>
        <a href="issuetickets.php">Back to Booking</a>
    </div>
</body>
</html>
