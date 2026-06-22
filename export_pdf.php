<?php

require('fpdf/fpdf.php');
require_once "config/koneksi.php";

$query = mysqli_query($conn,"
    SELECT
        r.*,
        k.merk_jenis
    FROM riwayat r
    LEFT JOIN kendaraan k
    ON r.kendaraan = k.no_polisi
    ORDER BY r.id DESC
");

$pdf = new FPDF('L','mm','A4');

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Laporan Riwayat Peminjaman',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(10,10,'No',1);
$pdf->Cell(40,10,'Nama',1);
$pdf->Cell(45,10,'Kendaraan',1);
$pdf->Cell(35,10,'Pengemudi',1);
$pdf->Cell(30,10,'Status',1);
$pdf->Cell(60,10,'Tujuan',1);
$pdf->Cell(50,10,'Tanggal',1);

$pdf->Ln();

$pdf->SetFont('Arial','',9);

$no = 1;

while($row = mysqli_fetch_assoc($query)){

    $pdf->Cell(10,10,$no++,1);
    $pdf->Cell(40,10,$row['nama_peminjam'],1);
    $pdf->Cell(45,10,$row['merk_jenis'],1);
    $pdf->Cell(35,10,$row['pengemudi'],1);
    $pdf->Cell(30,10,$row['status'],1);
    $pdf->Cell(60,10,$row['tujuan'],1);
    $pdf->Cell(50,10,$row['tanggal_pinjam'],1);

    $pdf->Ln();
}

$pdf->Output('I','Riwayat_Peminjaman.pdf');