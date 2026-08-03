<?php

require_once __DIR__ . "/../koneksi.php";

$kode = mysqli_real_escape_string($conn,$_POST['kode_mk']);
$nama = mysqli_real_escape_string($conn,$_POST['nama_mk']);
$sks  = mysqli_real_escape_string($conn,$_POST['sks']);

mysqli_query($conn,"
INSERT INTO mata_kuliah(kode_mk,nama_mk,sks)
VALUES('$kode','$nama','$sks')
");

header("Location:index.php");
exit;