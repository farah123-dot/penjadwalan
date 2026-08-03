<?php

require_once __DIR__ . "/../koneksi.php";

$id_jadwal = $_POST['id_jadwal'];
$id_hari = $_POST['id_hari'];
$id_jam = $_POST['id_jam'];
$id_ruangan = $_POST['id_ruangan'];
$id_dosen_mk = $_POST['id_dosen_mk'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id_dosen,id_kelas
FROM dosen_mk
WHERE id='$id_dosen_mk'
"));

$id_dosen = $data['id_dosen'];
$id_kelas = $data['id_kelas'];

/* CEK RUANGAN */

$cek = mysqli_query($conn,"
SELECT *
FROM jadwal
WHERE
id_hari='$id_hari'
AND id_jam='$id_jam'
AND id_ruangan='$id_ruangan'
AND id_jadwal<>'$id_jadwal'
");

if(mysqli_num_rows($cek)>0){

echo "<script>
alert('Ruangan sudah dipakai.');
history.back();
</script>";

exit;

}

/* CEK DOSEN */

$cek = mysqli_query($conn,"
SELECT j.*

FROM jadwal j

JOIN dosen_mk dm
ON j.id_dosen_mk=dm.id

WHERE

j.id_hari='$id_hari'
AND j.id_jam='$id_jam'
AND dm.id_dosen='$id_dosen'
AND j.id_jadwal<>'$id_jadwal'
");

if(mysqli_num_rows($cek)>0){

echo "<script>
alert('Dosen bentrok.');
history.back();
</script>";

exit;

}

/* CEK KELAS */

$cek = mysqli_query($conn,"
SELECT j.*

FROM jadwal j

JOIN dosen_mk dm
ON j.id_dosen_mk=dm.id

WHERE

j.id_hari='$id_hari'
AND j.id_jam='$id_jam'
AND dm.id_kelas='$id_kelas'
AND j.id_jadwal<>'$id_jadwal'
");

if(mysqli_num_rows($cek)>0){

echo "<script>
alert('Kelas bentrok.');
history.back();
</script>";

exit;

}

mysqli_query($conn,"
UPDATE jadwal
SET

id_hari='$id_hari',
id_jam='$id_jam',
id_ruangan='$id_ruangan',
id_dosen_mk='$id_dosen_mk'

WHERE id_jadwal='$id_jadwal'
");

header("Location:index.php");
exit;