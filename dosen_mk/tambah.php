<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

$dosen = mysqli_query($conn,"
SELECT *
FROM dosen
ORDER BY nama_dosen
");

$mk = mysqli_query($conn,"
SELECT *
FROM mata_kuliah
ORDER BY nama_mk
");

$kelas = mysqli_query($conn,"
SELECT *
FROM kelas
ORDER BY nama_kelas
");

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h4>

<i class="bi bi-plus-circle"></i>

Tambah Dosen Mengajar

</h4>

</div>

<div class="card-body">

<form action="simpan.php" method="POST">

<div class="mb-3">

<label class="form-label">

Dosen

</label>

<select
name="id_dosen"
class="form-select"
required>

<option value="">-- Pilih Dosen --</option>

<?php while($d=mysqli_fetch_assoc($dosen)){ ?>

<option value="<?= $d['id_dosen']; ?>">

<?= $d['nama_dosen']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Mata Kuliah

</label>

<select
name="id_mk"
class="form-select"
required>

<option value="">-- Pilih Mata Kuliah --</option>

<?php while($m=mysqli_fetch_assoc($mk)){ ?>

<option value="<?= $m['id_mk']; ?>">

<?= $m['kode_mk']; ?> - <?= $m['nama_mk']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Kelas

</label>

<select
name="id_kelas"
class="form-select"
required>

<option value="">-- Pilih Kelas --</option>

<?php while($k=mysqli_fetch_assoc($kelas)){ ?>

<option value="<?= $k['id_kelas']; ?>">

<?= $k['nama_kelas']; ?>

</option>

<?php } ?>

</select>

</div>

<button type="submit" class="btn btn-success">

<i class="bi bi-save"></i>

Simpan

</button>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>

</form>

</div>

</div>

</div>

<?php
include "../layout/footer.php";
?>