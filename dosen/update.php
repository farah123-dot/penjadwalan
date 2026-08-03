<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id_dosen'];

$nama = mysqli_real_escape_string($conn,$_POST['nama_dosen']);

mysqli_query($conn,"
UPDATE dosen
SET nama_dosen='$nama'
WHERE id_dosen='$id'
");

header("Location:index.php");
exit;