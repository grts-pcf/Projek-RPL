<?php
require_once "config/koneksi.php";

header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Maintenance.xls");

$tglAwal = $_GET['tglAwal'] ?? '';
$tglAkhir = $_GET['tglAkhir'] ?? '';

$where = "";

if($tglAwal != '' && $tglAkhir != ''){
    $where =
        "WHERE tanggal_service
         BETWEEN '$tglAwal'
         AND '$tglAkhir'";
}

$query = mysqli_query($conn,"
    SELECT *
    FROM jadwal_ganti_oli_kendaraan_operasional
    $where
    ORDER BY tanggal_service DESC
");
?>

<table border="1">
<tr>
    <th>No</th>
    <th>No Polisi</th>
    <th>Tanggal Service</th>
    <th>No SPK</th>
    <th>KM Ganti Oli</th>
    <th>KM Selanjutnya</th>
    <th>Keterangan</th>
</tr>

<?php
$no=1;
while($d = mysqli_fetch_assoc($query)):
?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['no_polisi'] ?></td>
    <td><?= $d['tanggal_service'] ?></td>
    <td><?= $d['no_spk'] ?></td>
    <td><?= $d['km_ganti_oli'] ?></td>
    <td><?= $d['km_ganti_oli_selanjutnya'] ?></td>
    <td style="white-space: pre-wrap;"><?= $d['keterangan'] ?></td>
</tr>

<?php endwhile; ?>
</table>