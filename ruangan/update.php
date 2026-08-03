<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id_ruangan'];

$nama = mysqli_real_escape_string($conn,$_POST['nama_ruangan']);

mysqli_query($conn,"
UPDATE ruangan
SET nama_ruangan='$nama'
WHERE id_ruangan='$id'
");

header("Location:index.php");
exit;