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

Tambah Ruangan

</h4>

</div>

<div class="card-body">

<form action="simpan.php" method="POST">

<div class="mb-3">

<label class="form-label">

Nama Ruangan

</label>

<input
type="text"
name="nama_ruangan"
class="form-control"
placeholder="Contoh : Lab Komputer 1"
required>

</div>

<button class="btn btn-success">

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