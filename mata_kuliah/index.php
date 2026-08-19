<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";

$query = mysqli_query($conn,"
SELECT *
FROM mata_kuliah
ORDER BY id_mk ASC
");

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between">

<h4>

<i class="bi bi-book-fill"></i>

Data Mata Kuliah

</h4>

<a href="tambah.php" class="btn btn-light">

<i class="bi bi-plus-circle"></i>

Tambah Mata Kuliah

</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>

<th width="60">No</th>

<th>Kode MK</th>

<th>Nama Mata Kuliah</th>

<th width="80">SKS</th>

<th width="180">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $row['kode_mk']; ?></td>

<td><?= $row['nama_mk']; ?></td>

<td><?= $row['sks']; ?></td>

<td>

<a
href="edit.php?id=<?= $row['id_mk']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

</a>

<a
href="hapus.php?id=<?= $row['id_mk']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php
include "../layout/footer.php";
?>
