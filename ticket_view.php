<?php
require "db.php";
require "lib/fpdf.php";

if (!isset($_GET['booking_id'])) {
    die("Invalid Ticket");
}

$booking_id = (int)$_GET['booking_id'];

$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM tickets t
    JOIN users u ON t.user_id = u.id
    WHERE t.booking_id = ?
");
$stmt->execute([$booking_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found");
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

/* Header */
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'CITY INFORMATION SYSTEM',0,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'OFFICIAL SERVICE TICKET',0,1,'C');
$pdf->Ln(5);

/* Ticket Details */
$pdf->SetFont('Arial','',11);

$pdf->Cell(50,8,'Ticket Number:',0,0);
$pdf->Cell(0,8,$ticket['ticket_number'],0,1);

$pdf->Cell(50,8,'Issued To:',0,0);
$pdf->Cell(0,8,$ticket['username'],0,1);

$pdf->Cell(50,8,'Service Type:',0,0);
$pdf->Cell(0,8,ucfirst($ticket['category']),0,1);

$pdf->Cell(50,8,'Service Name:',0,0);
$pdf->Cell(0,8,$ticket['service_name'],0,1);

$pdf->Cell(50,8,'Issued Date & Time:',0,0);
$pdf->Cell(0,8,$ticket['issued_date'].' '.$ticket['issued_time'],0,1);

$pdf->Ln(10);

/* Authority Section */
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,'Authorized By:',0,1);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,$ticket['authority_signature'],0,1);

$pdf->Ln(15);

/* Footer */
$pdf->SetFont('Arial','I',9);
$pdf->Cell(0,8,'This is a system-generated official ticket. No signature required.',0,1,'C');

/* IMPORTANT CHANGE HERE */
$pdf->Output("I", $ticket['ticket_number'] . ".pdf");
?>