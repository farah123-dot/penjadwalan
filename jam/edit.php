<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM jam_kuliah
WHERE id_jam='$id'
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

Edit Jam Kuliah

</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input
type="hidden"
name="id_jam"
value="<?= $data['id_jam']; ?>">

<div class="mb-3">

<label>Jam Mulai</label>

<input
type="time"
name="jam_mulai"
class="form-control"
value="<?= substr($data['jam_mulai'],0,5); ?>"
required>

</div>

<div class="mb-3">

<label>Jam Selesai</label>

<input
type="time"
name="jam_selesai"
class="form-control"
value="<?= substr($data['jam_selesai'],0,5); ?>"
required>

</div>

<button class="btn btn-warning">

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