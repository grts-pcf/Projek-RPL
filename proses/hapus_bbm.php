<?php
require_once "../config/koneksi.php";

if(isset($_GET['id']))
{
    $id = (int) $_GET['id'];

    mysqli_query(
        $conn,
        "DELETE FROM pengisian_bbm
        WHERE id='$id'"
    );
}

header("Location: ../jadwal.php");
exit;
?>