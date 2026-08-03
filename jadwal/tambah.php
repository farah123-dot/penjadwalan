<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

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

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h4>

<i class="bi bi-plus-circle"></i>

Tambah Jadwal Kuliah

</h4>

</div>

<div class="card-body">

<form action="simpan.php" method="POST">

<div class="mb-3">

<label class="form-label">

Hari

</label>

<select
name="id_hari"
class="form-select"
required>

<option value="">-- Pilih Hari --</option>

<?php while($h=mysqli_fetch_assoc($hari)){ ?>

<option value="<?= $h['id_hari']; ?>">

<?= $h['nama_hari']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Jam Kuliah

</label>

<select
name="id_jam"
class="form-select"
required>

<option value="">-- Pilih Jam --</option>

<?php while($j=mysqli_fetch_assoc($jam)){ ?>

<option value="<?= $j['id_jam']; ?>">

<?= substr($j['jam_mulai'],0,5); ?>

-

<?= substr($j['jam_selesai'],0,5); ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Ruangan

</label>

<select
name="id_ruangan"
class="form-select"
required>

<option value="">-- Pilih Ruangan --</option>

<?php while($r=mysqli_fetch_assoc($ruangan)){ ?>

<option value="<?= $r['id_ruangan']; ?>">

<?= $r['nama_ruangan']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Dosen Mengajar

</label>

<select
name="id_dosen_mk"
class="form-select"
required>

<option value="">-- Pilih Dosen Mengajar --</option>

<?php while($dm=mysqli_fetch_assoc($dosen_mk)){ ?>

<option value="<?= $dm['id']; ?>">

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

<button
type="submit"
class="btn btn-success">

<i class="bi bi-save"></i>

Simpan

</button>

<a
href="index.php"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

</div>

<?php
include "../layout/footer.php";
?>