<?php

require_once __DIR__ . "/../koneksi.php";

$id = $_POST['id'];

$id_dosen = $_POST['id_dosen'];

$id_mk = $_POST['id_mk'];

$id_kelas = $_POST['id_kelas'];

mysqli_query($conn,"
UPDATE dosen_mk
SET

id_dosen='$id_dosen',
id_mk='$id_mk',
id_kelas='$id_kelas'

WHERE id='$id'
");

header("Location:index.php");
exit;