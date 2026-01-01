<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_GET['service_id'] ?? null;
$amount     = $_GET['amount'] ?? null;
if (!$service_id || !$amount) {
    die("Invalid payment request");
}
$pid = "BOOK_" . time();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Payment</title>
    <meta charset="UTF-8">
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    background: #f4f7fb;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-box {
    background: #fff;
    width: 100%;
    max-width: 420px;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    text-align: center;
}

.payment-box h2 {
    margin-bottom: 20px;
    color: #3F84B1;
}

.payment-box p {
    margin: 12px 0;
    font-size: 1.05rem;
    color: #333;
}

.payment-box strong {
    color: #000;
}

.payment-box button {
    margin-top: 20px;
    background: #3F84B1;
    color: white;
    border: none;
    padding: 12px 22px;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s ease;
}

.payment-box button:hover {
    background: #2f6d93;
    transform: translateY(-2px);
}

.payment-box button:active {
    transform: translateY(0);
}
</style>

</head>

<body>
<div class="payment-box">
    <h2>Confirm Your Payment</h2>

    <p>
        <strong>Service ID:</strong> <?php echo htmlspecialchars($service_id); ?>
    </p>

    <p>
        <strong>Amount:</strong> Rs. <?php echo htmlspecialchars($amount); ?>
    </p>

    <form method="POST" action="esewa_process.php">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <button type="submit">Proceed to eSewa</button>
    </form>
</div>

</body>
</html>
