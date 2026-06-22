<?php
session_start();
require_once "../config/koneksi.php";

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = md5($_POST['password']);

$query = mysqli_query($conn,
    "SELECT * FROM admin
    WHERE username='$username'
    AND password='$password'"
);

if(mysqli_num_rows($query) == 1){

    $data = mysqli_fetch_assoc($query);

    $_SESSION['admin'] = $data['username'];
    $_SESSION['id_admin'] = $data['id_admin'];

    header("Location: ../index.php");
    exit();

}else{

    header("Location: ../LOGIN.php?error=1");
    exit();

}
?>