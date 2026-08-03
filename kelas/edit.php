<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM kelas
WHERE id_kelas='$id'
");

$data = mysqli_fetch_assoc($query);

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>

<i class="bi bi-pencil-square"></i>

Edit Kelas

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id_kelas"
value="<?= $data['id_kelas']; ?>">

<div class="mb-3">

<label>

Nama Kelas

</label>

<input
type="text"
name="nama_kelas"
class="form-control"
value="<?= $data['nama_kelas']; ?>"
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