<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM mata_kuliah
WHERE id_mk='$id'
");

$data = mysqli_fetch_assoc($query);

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h4>

<i class="bi bi-pencil-square"></i>

Edit Mata Kuliah

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id_mk"
value="<?= $data['id_mk']; ?>">

<div class="mb-3">

<label>Kode Mata Kuliah</label>

<input
type="text"
name="kode_mk"
class="form-control"
value="<?= $data['kode_mk']; ?>"
required>

</div>

<div class="mb-3">

<label>Nama Mata Kuliah</label>

<input
type="text"
name="nama_mk"
class="form-control"
value="<?= $data['nama_mk']; ?>"
required>

</div>

<div class="mb-3">

<label>SKS</label>

<input
type="number"
name="sks"
class="form-control"
value="<?= $data['sks']; ?>"
required>

</div>

<button class="btn btn-warning">

<i class="bi bi-save"></i>

Update

</button>

<a href="index.php"
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