<?php

require_once __DIR__ . "/../koneksi.php";

$nama_dosen = mysqli_real_escape_string($conn, $_POST['nama_dosen']);

mysqli_query($conn, "
INSERT INTO dosen(nama_dosen)
VALUES('$nama_dosen')
");

header("Location:index.php");
exit;