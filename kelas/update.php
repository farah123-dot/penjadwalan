<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id_kelas'];

$nama = mysqli_real_escape_string($conn,$_POST['nama_kelas']);

mysqli_query($conn,"
UPDATE kelas
SET nama_kelas='$nama'
WHERE id_kelas='$id'
");

header("Location:index.php");
exit;