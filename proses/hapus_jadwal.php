<?php
require_once "../config/koneksi.php";

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM jadwal_ganti_oli_kendaraan_operasional
        WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);
}

header("Location: ../jadwal.php");
exit;
?>