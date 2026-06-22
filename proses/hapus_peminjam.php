<?php
require_once "../config/koneksi.php";

if(isset($_GET['id'])){

    $id = (int)$_GET['id'];

    mysqli_query($conn, "
        DELETE FROM data_peminjam
        WHERE id = $id
    ");

}

header("Location: ../data-peminjam.php");
exit();