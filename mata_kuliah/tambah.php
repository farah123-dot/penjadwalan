<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h4>

Tambah Mata Kuliah

</h4>

</div>

<div class="card-body">

<form action="simpan.php" method="POST">

<div class="mb-3">

<label>Kode Mata Kuliah</label>

<input
type="text"
name="kode_mk"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Nama Mata Kuliah</label>

<input
type="text"
name="nama_mk"
class="form-control"
required>

</div>

<div class="mb-3">

<label>SKS</label>

<input
type="number"
name="sks"
class="form-control"
required>

</div>

<button class="btn btn-success">

Simpan

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