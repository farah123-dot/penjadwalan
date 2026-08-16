<?php

require_once "koneksi.php";

/* ===============================
   AMBIL DATA JADWAL
================================ */

$query = mysqli_query($conn, "

SELECT
    j.id_jadwal,
    h.nama_hari,
    jk.jam_mulai,
    jk.jam_selesai,
    r.nama_ruangan,
    k.nama_kelas,
    d.nama_dosen,
    mk.kode_mk,
    mk.nama_mk

FROM jadwal j

JOIN hari h
    ON j.id_hari = h.id_hari

JOIN jam_kuliah jk
    ON j.id_jam = jk.id_jam

JOIN ruangan r
    ON j.id_ruangan = r.id_ruangan

JOIN dosen_mk dm
    ON j.id_dosen_mk = dm.id

JOIN dosen d
    ON dm.id_dosen = d.id_dosen

JOIN mata_kuliah mk
    ON dm.id_mk = mk.id_mk

JOIN kelas k
    ON dm.id_kelas = k.id_kelas

ORDER BY
    h.id_hari,
    jk.jam_mulai,
    k.nama_kelas

");

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>SIKULIAH - Jadwal Kuliah</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<style>

body {
    background: #edf2f7;
}

/* NAVBAR */

.navbar-custom {
    background: white;
    box-shadow: 0 3px 15px rgba(0,0,0,.08);
}

.logo {
    font-size: 28px;
    font-weight: 700;
    color: #0d6efd;
}

.logo i {
    margin-right: 8px;
}

/* HERO */

.hero {
    background: #0d6efd;
    color: white;
    border-radius: 18px;
    padding: 40px;
    margin-top: 30px;
    margin-bottom: 30px;
}

.hero h1 {
    font-weight: 700;
}

/* CARD */

.card {
    border: none;
    border-radius: 15px;
}

/* TABLE */

.table th {
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.badge-kelas {
    background: #0d6efd;
    font-size: 13px;
}

</style>

</head>

<body>


<!-- ===============================
     NAVBAR
================================ -->

<nav class="navbar navbar-expand-lg navbar-custom">

<div class="container">

<a class="navbar-brand logo" href="/">

<i class="bi bi-mortarboard-fill"></i>
SIKULIAH

</a>


<div class="ms-auto d-flex gap-2">

<a href="/auth/login.php"
   class="btn btn-outline-primary">

<i class="bi bi-box-arrow-in-right"></i>
Login

</a>


<a href="/auth/register.php"
   class="btn btn-primary">

<i class="bi bi-person-plus"></i>
Register

</a>

</div>

</div>

</nav>



<!-- ===============================
     CONTENT
================================ -->

<div class="container">


<!-- HERO -->

<div class="hero text-center">

<h1>
<i class="bi bi-calendar-week"></i>
Sistem Penjadwalan Kuliah
</h1>

<p class="mb-0">
Informasi jadwal perkuliahan
</p>

</div>



<!-- JADWAL -->

<div class="card shadow mb-5">

<div class="card-header bg-white">

<h4 class="mb-0">

<i class="bi bi-calendar3 text-primary"></i>

Jadwal Perkuliahan

</h4>

</div>


<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-primary text-center">

<tr>

<th>No</th>

<th>Hari</th>

<th>Jam</th>

<th>Kelas</th>

<th>Kode MK</th>

<th>Mata Kuliah</th>

<th>Dosen</th>

<th>Ruangan</th>

</tr>

</thead>


<tbody>

<?php

if(mysqli_num_rows($query) > 0){

$no = 1;

while($row = mysqli_fetch_assoc($query)){

?>

<tr>

<td class="text-center">
<?= $no++; ?>
</td>


<td>
<?= $row['nama_hari']; ?>
</td>


<td class="text-center">

<?= substr($row['jam_mulai'],0,5); ?>

-

<?= substr($row['jam_selesai'],0,5); ?>

</td>


<td class="text-center">

<span class="badge badge-kelas">

<?= $row['nama_kelas']; ?>

</span>

</td>


<td>
<?= $row['kode_mk']; ?>
</td>


<td>
<?= $row['nama_mk']; ?>
</td>


<td>
<?= $row['nama_dosen']; ?>
</td>


<td>
<?= $row['nama_ruangan']; ?>
</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="8"
    class="text-center">

Belum ada jadwal perkuliahan.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>


</div>


</body>

</html>
