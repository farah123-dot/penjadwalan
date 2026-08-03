<?php

require_once __DIR__ . "/../koneksi.php";

$nama = mysqli_real_escape_string($conn,$_POST['nama_kelas']);

mysqli_query($conn,"
INSERT INTO kelas(nama_kelas)
VALUES('$nama')
");

header("Location:index.php");
exit;