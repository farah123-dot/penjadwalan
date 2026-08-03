<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM dosen WHERE id_dosen='$id'")
);

include "../layout/header.php";
include "../layout/sidebar.php";

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="bi bi-pencil-square"></i>

Edit Data Dosen

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id_dosen"
value="<?= $data['id_dosen']; ?>">

<div class="mb-3">

<label class="form-label">

Nama Dosen

</label>

<input
type="text"
name="nama_dosen"
class="form-control"
value="<?= $data['nama_dosen']; ?>"
required>

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