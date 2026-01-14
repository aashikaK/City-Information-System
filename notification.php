<?php
session_start();
require "db.php";

if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];

// ------------------ FETCH TICKET NOTIFICATIONS ------------------
// Only tickets with status 'issued' for this user
$stmt_tickets = $pdo->prepare("
    SELECT t.ticket_number, t.booking_id, t.service_name, t.category, t.issued_date, t.issued_time
    FROM tickets t
    WHERE t.user_id = ? AND t.status = 'issued'
    ORDER BY t.created_at DESC
");
$stmt_tickets->execute([$user_id]);
$tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);

// ------------------ COUNT TICKET NOTIFICATIONS ------------------
$ticket_count = count($tickets);
?>

<div id="notifications" style="width:350px;padding:15px;background:#f4f7fb;border:1px solid #ccc;border-radius:8px;">
    <h3>Tickets (<?php echo $ticket_count; ?>)</h3>
    
    <?php if($ticket_count > 0): ?>
        <ul>
        <?php foreach($tickets as $ticket): ?>
            <li style="margin-bottom:8px;">
                <strong><?php echo htmlspecialchars($ticket['service_name']); ?></strong> (<?php echo ucfirst($ticket['category']); ?>)<br>
                Issued: <?php echo $ticket['issued_date'].' '.$ticket['issued_time']; ?><br>
                <a href="ticket_view.php?booking_id=<?php echo $ticket['booking_id']; ?>" target="_blank">Download Ticket PDF</a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No new tickets</p>
    <?php endif; ?>
</div>
