<?php

require_once "../config/koneksi.php";

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $hapus = mysqli_query(
        $conn,
        "DELETE FROM supir
        WHERE id = '$id'"
    );

    if ($hapus) {

        header("Location: ../pengemudi.php");
        exit();

    } else {

        echo "
        <script>
            alert('Data pengemudi gagal dihapus!');
            window.location='../pengemudi.php';
        </script>
        ";

    }

} else {

    header("Location: ../pengemudi.php");
    exit();

}