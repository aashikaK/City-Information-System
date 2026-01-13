<?php
require "db.php";
require "lib/fpdf.php";

if (!isset($_GET['booking_id'])) {
    die("Invalid Ticket");
}

$booking_id = (int)$_GET['booking_id'];

// Fetch ticket info with user profile
$stmt = $pdo->prepare("
    SELECT t.*, u.username, up.profile_pic
    FROM tickets t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN user_profiles up ON u.id = up.user_id
    WHERE t.booking_id = ?
");
$stmt->execute([$booking_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    die("Ticket not found");
}

// Create PDF
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

/* User Image for Verification */
if (!empty($ticket['profile_pic']) && file_exists($ticket['profile_pic'])) {
    $x = 153; // position from left
    $y = 34;  // position from top
    $w = 32;  // width
    $h = 37;  // height
    $pdf->Image($ticket['profile_pic'], $x, $y, $w, $h);
}

/* Authority Section */
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,'Authorized By:',0,1);

// Simple slanted bold signature, all letters in same line
$signature = "AashikaK";
$pdf->SetFont('times','BI',28); // bold + italic
$pdf->SetTextColor(50,50,50);   // dark gray

$startX = 20;                   // starting X position
$startY = $pdf->GetY();          // starting Y position
$spacing = 5;                    // small spacing between letters

foreach(str_split($signature) as $i => $letter){
    $pdf->SetXY($startX + ($i * $spacing), $startY);
    $pdf->Cell(5,12,$letter,0,0,'L'); // all letters aligned, same line
}

$pdf->Ln(15);

/* Footer */
$pdf->SetFont('Arial','I',9);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,8,'This is a system-generated official ticket. No signature required.',0,1,'C');

/* Display PDF in browser */
$pdf->Output("I", $ticket['ticket_number'] . ".pdf");
?>
