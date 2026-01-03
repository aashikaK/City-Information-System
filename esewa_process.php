<?php
session_start();


if (!isset($_SESSION['login'])) {
    header("Location: signin.php");
    exit;
}


$service_id = $_SESSION['service_id'] ?? null;
$amount     = $_SESSION['amount'] ?? null;

if (!$service_id || !$amount) {
    die("Invalid payment request");
}
$transaction_uuid = "CIS_" . time() . rand(1000,9999);
$_SESSION['transaction_uuid'] = $transaction_uuid;

$esewa_url = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";

//  Merchant product code (TEST)
$product_code = "EPAYTEST";

//  Charges (keep 0 for project)
$tax_amount = 0;
$product_service_charge = 0;
$product_delivery_charge = 0;

//  URLs (LOCALHOST allowed for demo)
$success_url = "http://localhost/CIS/esewa_success.php";
$failure_url = "http://localhost/CIS/esewa_fail.php";

//  Fields that must be signed (ORDER MATTERS!)
$signed_field_names = "total_amount,transaction_uuid,product_code";

$signature_string =
    "total_amount={$amount}," .
    "transaction_uuid={$transaction_uuid}," .
    "product_code={$product_code}";

    //  eSewa UAT secret key
$secret_key = "8gBm/:&EnhH.1/q";

//  Generate signature (HMAC SHA256 + Base64)
$signature = base64_encode(
    hash_hmac("sha256", $signature_string, $secret_key, true)
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Redirecting to eSewa</title>
</head>
<body>

<p>Redirecting to eSewa...</p>

<form id="esewaForm" action="<?php echo $esewa_url; ?>" method="POST">

    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>">
    <input type="hidden" name="total_amount" value="<?php echo $amount; ?>">

    <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
    <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">

    <input type="hidden" name="product_service_charge" value="<?php echo $product_service_charge; ?>">
    <input type="hidden" name="product_delivery_charge" value="<?php echo $product_delivery_charge; ?>">

    <input type="hidden" name="success_url" value="<?php echo $success_url; ?>">
    <input type="hidden" name="failure_url" value="<?php echo $failure_url; ?>">

    <input type="hidden" name="signed_field_names" value="<?php echo $signed_field_names; ?>">
    <input type="hidden" name="signature" value="<?php echo $signature; ?>">

</form>

<script>
    document.getElementById("esewaForm").submit();
</script>

</body>
</html>