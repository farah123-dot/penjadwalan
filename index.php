<?php
require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar.php";


/* ===============================
   FUNGSI HITUNG DATA
================================ */

function jumlahData($conn, $tabel)
{
    $query = mysqli_query($conn, "SELECT * FROM $tabel");

    if ($query) {
        return mysqli_num_rows($query);
    }

    return 0;
}


/* ===============================
   JUMLAH DATA
================================ */

$jumlah_dosen     = jumlahData($conn, "dosen");
$jumlah_mk        = jumlahData($conn, "mata_kuliah");
$jumlah_kelas     = jumlahData($conn, "kelas");
$jumlah_ruangan   = jumlahData($conn, "ruangan");
$jumlah_jam       = jumlahData($conn, "jam_kuliah");
$jumlah_jadwal    = jumlahData($conn, "jadwal");


/* ===============================
   AMBIL DATA JADWAL
================================ */

$query_jadwal = mysqli_query($conn, "

SELECT 
    jadwal.id_jadwal,
    kelas.nama_kelas,
    hari.nama_hari,
    jam_kuliah.jam_mulai,
    jam_kuliah.jam_selesai,
    ruangan.nama_ruangan,
    dosen.nama_dosen,
    mata_kuliah.nama_mk

FROM jadwal


JOIN hari
ON jadwal.id_hari = hari.id_hari


JOIN jam_kuliah
ON jadwal.id_jam = jam_kuliah.id_jam


JOIN ruangan
ON jadwal.id_ruangan = ruangan.id_ruangan


JOIN dosen_mk
ON jadwal.id_dosen_mk = dosen_mk.id


JOIN dosen
ON dosen_mk.id_dosen = dosen.id_dosen


JOIN mata_kuliah
ON dosen_mk.id_mk = mata_kuliah.id_mk


JOIN kelas
ON dosen_mk.id_kelas = kelas.id_kelas


ORDER BY 
hari.id_hari ASC,
jam_kuliah.jam_mulai ASC

");
?>


<div class="container-fluid">

<h3 class="mb-4">
    Dashboard SIKULIAH
</h3>


<!-- CARD JUMLAH DATA -->

<div class="row">


<div class="col-md-3">
<div class="card shadow mb-3">
<div class="card-body">

<h6>Data Dosen</h6>

<h2>
<?= $jumlah_dosen ?>
</h2>

</div>
</div>
</div>



<div class="col-md-3">
<div class="card shadow mb-3">
<div class="card-body">

<h6>Mata Kuliah</h6>

<h2>
<?= $jumlah_mk ?>
</h2>

</div>
</div>
</div>



<div class="col-md-3">
<div class="card shadow mb-3">
<div class="card-body">

<h6>Kelas</h6>

<h2>
<?= $jumlah_kelas ?>
</h2>

</div>
</div>
</div>



<div class="col-md-3">
<div class="card shadow mb-3">
<div class="card-body">

<h6>Jadwal</h6>

<h2>
<?= $jumlah_jadwal ?>
</h2>

</div>
</div>
</div>


</div>



<!-- TABEL JADWAL -->

<div class="card shadow">

<div class="card-header d-flex justify-content-between align-items-center">

<h5>
Jadwal Perkuliahan
</h5>

<a href="export_jadwal.php" class="btn btn-danger btn-sm">
    Export PDF
</a>

</div>


<div class="card-body">

<div class="table-responsive">

<?php
$daftar_warna = [
    "#0d6efd",
    "#198754",
    "#dc3545",
    "#ffc107",
    "#6f42c1",
    "#20c997",
    "#fd7e14",
    "#6610f2"
];

$warna_mk = [];
$index_warna = 0;
?>

<table class="table table-bordered text-center">


<thead class="table-dark">


<tr>

<th width="120">
Hari
</th>


<th width="120">
Waktu
</th>


<?php

$data_ruangan = mysqli_query($conn,"
SELECT * FROM ruangan
ORDER BY id_ruangan ASC
");


while($ruangan=mysqli_fetch_assoc($data_ruangan)){

?>

<th>
<?= $ruangan['nama_ruangan'] ?>
</th>


<?php } ?>


</tr>


</thead>



<tbody>


<?php


$data_hari = mysqli_query($conn,"
SELECT * FROM hari
ORDER BY id_hari ASC
");


while($hari=mysqli_fetch_assoc($data_hari)){


$data_jam = mysqli_query($conn,"
SELECT * FROM jam_kuliah
ORDER BY id_jam ASC
");


$first=true;


while($jam=mysqli_fetch_assoc($data_jam)){


?>


<tr>


<?php if($first){ ?>


<td rowspan="<?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM jam_kuliah")) ?>"
class="fw-bold align-middle">

<?= $hari['nama_hari'] ?>

</td>


<?php 

$first=false;

} 

?>


<td>

<?= $jam['jam_mulai'] ?>
-
<?= $jam['jam_selesai'] ?>

</td>




<?php


$data_ruangan2 = mysqli_query($conn,"
SELECT * FROM ruangan
ORDER BY id_ruangan ASC
");


while($ruang=mysqli_fetch_assoc($data_ruangan2)){



$jadwal=mysqli_query($conn,"


SELECT 

mata_kuliah.nama_mk,
dosen.nama_dosen,
kelas.nama_kelas


FROM jadwal


JOIN dosen_mk
ON jadwal.id_dosen_mk=dosen_mk.id


JOIN mata_kuliah
ON dosen_mk.id_mk=mata_kuliah.id_mk


JOIN dosen
ON dosen_mk.id_dosen=dosen.id_dosen


JOIN kelas
ON dosen_mk.id_kelas=kelas.id_kelas


WHERE

jadwal.id_hari='$hari[id_hari]'

AND

jadwal.id_jam='$jam[id_jam]'

AND

jadwal.id_ruangan='$ruang[id_ruangan]'


");



if(mysqli_num_rows($jadwal)>0){


$data=mysqli_fetch_assoc($jadwal);


$nama_mk = $data['nama_mk'];

if (!isset($warna_mk[$nama_mk])) {

    $warna_mk[$nama_mk] = 
    $daftar_warna[$index_warna % count($daftar_warna)];

    $index_warna++;
}

$warna = $warna_mk[$nama_mk];

?>

<td style="
background-color: <?= $warna ?>;
color:white;
text-align:center;
">

<b>
<?= $data['nama_mk'] ?>
</b>

<br>

<small>
<?= $data['nama_dosen'] ?>
</small>

<br>

<small>
<?= $data['nama_kelas'] ?>
</small>

</td>

<?php

}else{

?>

<td>
-
</td>

<?php

}


}


}


?>


</tr>


<?php


}


?>


</tbody>


</table>


</div>

</div>

</div>

<?php

include "layout/footer.php";

?>