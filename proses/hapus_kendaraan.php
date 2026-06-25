<?php

require_once "../config/koneksi.php";

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $query = mysqli_query(
        $conn,
        "DELETE FROM kendaraan
         WHERE id_kendaraan = '$id'"
    );

    if($query)
    {
        header("Location: ../kendaraan.php");
        exit();
    }
    else
    {
        echo "Gagal menghapus kendaraan: "
             . mysqli_error($conn);
    }
}
else
{
    header("Location: ../kendaraan.php");
    exit();
}