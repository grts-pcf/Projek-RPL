<?php
require "../config/koneksi.php";

$tglPinjam = $_POST['tanggal_pinjam'] ?? '';
$jamBerangkat = $_POST['jam_berangkat'] ?? '';
$tglKembali = $_POST['tanggal_kembali'] ?? '';
$jamKembali = $_POST['jam_kembali'] ?? '';

$mulai = $tglPinjam . ' ' . $jamBerangkat;
$selesai = $tglKembali . ' ' . $jamKembali;

$data = [];

$query = mysqli_query($conn,"
SELECT
    k.*,

    (
        SELECT j.status
        FROM jadwal_ganti_oli_kendaraan_operasional j
        WHERE j.id_kendaraan = k.id_kendaraan
        AND j.status='Maintenance'
        LIMIT 1
    ) AS status_maintenance,

    (
        SELECT COUNT(*)
        FROM riwayat r
        WHERE r.kendaraan = k.no_polisi
        AND r.status='disetujui'
        AND (
            '$mulai' < CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
            AND
            '$selesai' > CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
        )
    ) AS bentrok

FROM kendaraan k
ORDER BY k.merk_jenis
");

while($k = mysqli_fetch_assoc($query))
{
    $status = "Tersedia";

    if(!empty($k['status_maintenance']))
    {
        $status = "Maintenance";
    }
    elseif($k['bentrok'] > 0)
    {
        $status = "Dipakai";
    }

    $k['status_kendaraan'] = $status;

    $data[] = $k;
}

$pengemudi = [];

$querySupir = mysqli_query($conn,"
SELECT
    s.*,

    (
        SELECT COUNT(*)
        FROM riwayat r
        WHERE r.pengemudi = s.nama_supir
        AND r.status='disetujui'
        AND (
            '$mulai' < CONCAT(r.tanggal_kembali,' ',r.jam_kembali)
            AND
            '$selesai' > CONCAT(r.tanggal_pinjam,' ',r.jam_berangkat)
        )
    ) AS bentrok

FROM supir s
ORDER BY s.nama_supir
");

while($s = mysqli_fetch_assoc($querySupir))
{
    $s['status_supir'] =
        ($s['bentrok'] > 0)
        ? 'Bertugas'
        : 'Tersedia';

    $pengemudi[] = $s;
}

echo json_encode([
    'kendaraan' => $data,
    'pengemudi' => $pengemudi
]);