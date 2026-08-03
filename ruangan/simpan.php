<?php

require_once __DIR__ . "/../koneksi.php";

$nama = mysqli_real_escape_string($conn,$_POST['nama_ruangan']);

mysqli_query($conn,"
INSERT INTO ruangan(nama_ruangan)
VALUES('$nama')
");

header("Location:index.php");
exit;