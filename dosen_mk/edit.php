<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM dosen_mk
WHERE id='$id'
"));

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

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h4>

<i class="bi bi-pencil-square"></i>

Edit Dosen Mengajar

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id"
value="<?= $data['id']; ?>">

<div class="mb-3">

<label class="form-label">

Dosen

</label>

<select
name="id_dosen"
class="form-select"
required>

<?php while($d=mysqli_fetch_assoc($dosen)){ ?>

<option
value="<?= $d['id_dosen']; ?>"
<?= ($d['id_dosen']==$data['id_dosen']) ? "selected" : ""; ?>>

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

<?php while($m=mysqli_fetch_assoc($mk)){ ?>

<option
value="<?= $m['id_mk']; ?>"
<?= ($m['id_mk']==$data['id_mk']) ? "selected" : ""; ?>>

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

<?php while($k=mysqli_fetch_assoc($kelas)){ ?>

<option
value="<?= $k['id_kelas']; ?>"
<?= ($k['id_kelas']==$data['id_kelas']) ? "selected" : ""; ?>>

<?= $k['nama_kelas']; ?>

</option>

<?php } ?>

</select>

</div>

<button type="submit" class="btn btn-warning">

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