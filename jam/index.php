<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

$query = mysqli_query($conn,"SELECT * FROM jam_kuliah ORDER BY jam_mulai ASC");

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">

<i class="bi bi-clock-fill"></i>

Data Jam Kuliah

</h4>

<a href="tambah.php" class="btn btn-light">

<i class="bi bi-plus-circle"></i>

Tambah Jam

</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary text-center">

<tr>

<th width="70">No</th>

<th>Jam Mulai</th>

<th>Jam Selesai</th>

<th width="180">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query)>0){

$no=1;

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td class="text-center"><?= $no++; ?></td>

<td><?= substr($row['jam_mulai'],0,5); ?></td>

<td><?= substr($row['jam_selesai'],0,5); ?></td>

<td class="text-center">

<a href="edit.php?id=<?= $row['id_jam']; ?>" class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a href="hapus.php?id=<?= $row['id_jam']; ?>" class="btn btn-danger btn-sm"

onclick="return confirm('Yakin ingin menghapus data?')">

<i class="bi bi-trash"></i>

Hapus

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="4" class="text-center">

Belum ada data jam kuliah.

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