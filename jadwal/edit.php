<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM jadwal
WHERE id_jadwal='$id'
"));

$hari = mysqli_query($conn,"
SELECT *
FROM hari
ORDER BY id_hari
");

$jam = mysqli_query($conn,"
SELECT *
FROM jam_kuliah
ORDER BY jam_mulai
");

$ruangan = mysqli_query($conn,"
SELECT *
FROM ruangan
ORDER BY nama_ruangan
");

$dosen_mk = mysqli_query($conn,"
SELECT
dm.id,
k.nama_kelas,
mk.kode_mk,
mk.nama_mk,
d.nama_dosen

FROM dosen_mk dm

JOIN kelas k
ON dm.id_kelas=k.id_kelas

JOIN mata_kuliah mk
ON dm.id_mk=mk.id_mk

JOIN dosen d
ON dm.id_dosen=d.id_dosen

ORDER BY
k.nama_kelas,
mk.nama_mk
");

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>

<i class="bi bi-pencil-square"></i>

Edit Jadwal Kuliah

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id_jadwal"
value="<?= $data['id_jadwal']; ?>">

<div class="mb-3">

<label>Hari</label>

<select
name="id_hari"
class="form-select"
required>

<?php while($h=mysqli_fetch_assoc($hari)){ ?>

<option
value="<?= $h['id_hari']; ?>"
<?= ($data['id_hari']==$h['id_hari'])?'selected':''; ?>>

<?= $h['nama_hari']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Jam Kuliah</label>

<select
name="id_jam"
class="form-select"
required>

<?php while($j=mysqli_fetch_assoc($jam)){ ?>

<option
value="<?= $j['id_jam']; ?>"
<?= ($data['id_jam']==$j['id_jam'])?'selected':''; ?>>

<?= substr($j['jam_mulai'],0,5); ?>

-

<?= substr($j['jam_selesai'],0,5); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Ruangan</label>

<select
name="id_ruangan"
class="form-select"
required>

<?php while($r=mysqli_fetch_assoc($ruangan)){ ?>

<option
value="<?= $r['id_ruangan']; ?>"
<?= ($data['id_ruangan']==$r['id_ruangan'])?'selected':''; ?>>

<?= $r['nama_ruangan']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Dosen Mengajar</label>

<select
name="id_dosen_mk"
class="form-select"
required>

<?php while($dm=mysqli_fetch_assoc($dosen_mk)){ ?>

<option
value="<?= $dm['id']; ?>"
<?= ($data['id_dosen_mk']==$dm['id'])?'selected':''; ?>>

<?= $dm['nama_kelas']; ?>

|
<?= $dm['kode_mk']; ?>

-
<?= $dm['nama_mk']; ?>

|
<?= $dm['nama_dosen']; ?>

</option>

<?php } ?>

</select>

</div>

<button class="btn btn-warning">

<i class="bi bi-save"></i>

Update

</button>

<a href="index.php" class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

</div>

<?php
include "../layout/footer.php";
?>