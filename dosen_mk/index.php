<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";

$query = mysqli_query($conn,"
SELECT
dm.id,
d.nama_dosen,
mk.kode_mk,
mk.nama_mk,
k.nama_kelas

FROM dosen_mk dm

JOIN dosen d
ON dm.id_dosen = d.id_dosen

JOIN mata_kuliah mk
ON dm.id_mk = mk.id_mk

JOIN kelas k
ON dm.id_kelas = k.id_kelas

ORDER BY
k.nama_kelas ASC,
d.nama_dosen ASC
");

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h4 class="mb-0">

<i class="bi bi-person-workspace"></i>

Data Dosen Mengajar

</h4>

<a href="tambah.php" class="btn btn-light">

<i class="bi bi-plus-circle"></i>

Tambah Data

</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary text-center">

<tr>

<th width="60">No</th>

<th>Kelas</th>

<th>Dosen</th>

<th>Kode MK</th>

<th>Mata Kuliah</th>

<th width="180">Aksi</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($query) > 0){

$no = 1;

while($row = mysqli_fetch_assoc($query)){

?>

<tr>

<td class="text-center"><?= $no++; ?></td>

<td><?= $row['nama_kelas']; ?></td>

<td><?= $row['nama_dosen']; ?></td>

<td><?= $row['kode_mk']; ?></td>

<td><?= $row['nama_mk']; ?></td>

<td class="text-center">

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>
Edit

</a>

<a
href="hapus.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
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

<td colspan="6" class="text-center">

Belum ada data dosen mengajar.

</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php
include "../layout/footer.php";
?>
