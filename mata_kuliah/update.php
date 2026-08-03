<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id_mk'];

$kode = mysqli_real_escape_string($conn,$_POST['kode_mk']);
$nama = mysqli_real_escape_string($conn,$_POST['nama_mk']);
$sks  = mysqli_real_escape_string($conn,$_POST['sks']);

mysqli_query($conn,"
UPDATE mata_kuliah
SET
kode_mk='$kode',
nama_mk='$nama',
sks='$sks'
WHERE id_mk='$id'
");

header("Location:index.php");
exit;