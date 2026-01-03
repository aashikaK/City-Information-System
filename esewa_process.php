<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}

$service_id = $_POST['service_id'] ?? null;
$amount     = $_POST['amount'] ?? null;

if (!$service_id || !$amount) {
    die("Invalid payment request");
}
if (
    $service_id != $_SESSION['service_id'] ||
    $amount != $_SESSION['amount']
) {
    die("Payment data mismatch");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa</title>
</head>
<body>
    <form method="POST" action="https://esewa.com.np/epay/main" id="esewa_form">
    <input value="<?php echo $amount; ?>" name="amt" type="hidden">
    <input value="<?php echo $amount; ?>" name="tAmt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="<?php echo $_SESSION['pid']; ?>" name="pid" type="hidden">
    <input value="https://rattiest-kairi-nondiastasic.ngrok-free.dev/CIS/esewa_success.php" name="su" type="hidden">
<input value="https://rattiest-kairi-nondiastasic.ngrok-free.dev/CIS/esewa_fail.php" name="fu" type="hidden">

</form>
<script>
    document.getElementById('esewa_form').submit();
</script>

</body>
</html>