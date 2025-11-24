<?php
require "db.php";
session_start();

// ---------- MODE B: AFTER PAYMENT (SUCCESS OR FAILURE) ----------
if (isset($_GET['refId'])) {

    $refId = $_GET['refId'];  // eSewa reference ID
    $oid   = $_GET['oid'];    // your PID
    $amt   = $_GET['amt'];    // amount paid

    // Verify with eSewa server
    $url = "https://uat.esewa.com.np/epay/transrec";
    $data = [
        'amt' => $amt,
        'scd' => 'EPAYTEST',
        'rid' => $refId,
        'pid' => $oid
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    // SUCCESS
    if (strpos($response, "<response_code>Success</response_code>") !== false) {

        $parts = explode("_", $oid);
        $service_id = $parts[1];
        $user_id = $_SESSION['user_id'];

        // Insert booking
        $insert = $pdo->prepare("INSERT INTO bookings (user_id, service_id, status, booking_date, payment_ref)
                                 VALUES (?, ?, 'paid', CURDATE(), ?)");
        $insert->execute([$user_id, $service_id, $refId]);

        // Update capacity
        $update = $pdo->prepare("UPDATE city_services SET current_bookings = current_bookings + 1 WHERE id = ?");
        $update->execute([$service_id]);

        echo "<h2>Payment Successful!</h2>";
        echo "<p>Your booking is confirmed.</p>";
        exit;
    }

    // FAILURE
    echo "<h2>Payment Failed</h2>";
    echo "<p>Please try again.</p>";
    exit;
}


// --------- MODE A: INITIAL PAYMENT PAGE ---------
$service_id = $_GET['service_id'];
$amount     = $_GET['amount'];

$success_url = "http://yourdomain.com/esewa_payment.php"; // same page
$failure_url = "http://yourdomain.com/esewa_payment.php"; // same page

?>

<h2>Pay with eSewa</h2>

<form action="https://uat.esewa.com.np/epay/main" method="POST">
    <input value="<?php echo $amount; ?>" name="tAmt" type="hidden">
    <input value="<?php echo $amount; ?>" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">

    <input value="EPAYTEST" name="scd" type="hidden">

    <!-- pid must be same pattern -->
    <input value="BOOK_<?php echo $service_id . '_' . time(); ?>" name="pid" type="hidden">

    <input value="<?php echo $success_url; ?>" name="su" type="hidden">
    <input value="<?php echo $failure_url; ?>" name="fu" type="hidden">

    <button type="submit">Pay with eSewa</button>
</form>
