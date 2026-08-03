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

<i class="bi bi-plus-circle"></i>

Tambah Jam Kuliah

</h4>

</div>

<div class="card-body">

<form action="simpan.php" method="POST">

<div class="mb-3">

<label>Jam Mulai</label>

<input
type="time"
name="jam_mulai"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Jam Selesai</label>

<input
type="time"
name="jam_selesai"
class="form-control"
required>

</div>

<button class="btn btn-success">

<i class="bi bi-save"></i>

Simpan

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