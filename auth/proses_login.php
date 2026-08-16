<?php

session_start();

require_once "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "
    SELECT *
    FROM user
    WHERE username='$username'
    AND password='$password'
");

if (mysqli_num_rows($query) > 0) {

    $data = mysqli_fetch_assoc($query);

    $_SESSION['login'] = true;

    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['id_dosen'] = $data['id_dosen'];

    header("Location: index.php");
    exit;

} else {

    echo "
    <script>
        alert('Username atau Password salah');
        window.location='login.php';
    </script>
    ";

}
