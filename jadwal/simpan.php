<?php

require_once __DIR__ . "/../koneksi.php";

$id_hari      = $_POST['id_hari'];
$id_jam       = $_POST['id_jam'];
$id_ruangan   = $_POST['id_ruangan'];
$id_dosen_mk  = $_POST['id_dosen_mk'];

/*
==================================================
AMBIL DATA DOSEN DAN KELAS
==================================================
*/

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
id_dosen,
id_kelas
FROM dosen_mk
WHERE id='$id_dosen_mk'
"));

$id_dosen = $data['id_dosen'];
$id_kelas = $data['id_kelas'];

/*
==================================================
CEK BENTROK RUANGAN
==================================================
*/

$cek_ruangan = mysqli_query($conn,"
SELECT *
FROM jadwal
WHERE
id_hari='$id_hari'
AND id_jam='$id_jam'
AND id_ruangan='$id_ruangan'
");

if(mysqli_num_rows($cek_ruangan)>0){

echo "

<script>

alert('Ruangan sudah digunakan pada jam tersebut!');

window.location='tambah.php';

</script>

";

exit;

}

/*
==================================================
CEK BENTROK DOSEN
==================================================
*/

$cek_dosen = mysqli_query($conn,"
SELECT j.*

FROM jadwal j

JOIN dosen_mk dm
ON j.id_dosen_mk=dm.id

WHERE

j.id_hari='$id_hari'

AND

j.id_jam='$id_jam'

AND

dm.id_dosen='$id_dosen'
");

if(mysqli_num_rows($cek_dosen)>0){

echo "

<script>

alert('Dosen sudah memiliki jadwal pada jam tersebut!');

window.location='tambah.php';

</script>

";

exit;

}

/*
==================================================
CEK BENTROK KELAS
==================================================
*/

$cek_kelas = mysqli_query($conn,"
SELECT j.*

FROM jadwal j

JOIN dosen_mk dm
ON j.id_dosen_mk=dm.id

WHERE

j.id_hari='$id_hari'

AND

j.id_jam='$id_jam'

AND

dm.id_kelas='$id_kelas'
");

if(mysqli_num_rows($cek_kelas)>0){

echo "

<script>

alert('Kelas sudah memiliki jadwal pada jam tersebut!');

window.location='tambah.php';

</script>

";

exit;

}

/*
==================================================
SIMPAN DATA
==================================================
*/

$sql = mysqli_query($conn,"
INSERT INTO jadwal
(
id_hari,
id_jam,
id_ruangan,
id_dosen_mk
)
VALUES
(
'$id_hari',
'$id_jam',
'$id_ruangan',
'$id_dosen_mk'
)
");

if($sql){

echo "

<script>

alert('Jadwal berhasil disimpan.');

window.location='index.php';

</script>

";

}else{

echo "

<script>

alert('Gagal menyimpan jadwal!');

window.location='tambah.php';

</script>

";

}

?>