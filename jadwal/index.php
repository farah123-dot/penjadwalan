<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar.php";

$query = mysqli_query($conn, "
SELECT
    j.id_jadwal,
    h.nama_hari,
    jk.jam_mulai,
    jk.jam_selesai,
    r.nama_ruangan,
    k.nama_kelas,
    d.nama_dosen,
    mk.kode_mk,
    mk.nama_mk

FROM jadwal j

INNER JOIN hari h
    ON j.id_hari = h.id_hari

INNER JOIN jam_kuliah jk
    ON j.id_jam = jk.id_jam

INNER JOIN ruangan r
    ON j.id_ruangan = r.id_ruangan

INNER JOIN dosen_mk dm
    ON j.id_dosen_mk = dm.id

INNER JOIN dosen d
    ON dm.id_dosen = d.id_dosen

INNER JOIN mata_kuliah mk
    ON dm.id_mk = mk.id_mk

INNER JOIN kelas k
    ON dm.id_kelas = k.id_kelas

ORDER BY
    h.id_hari,
    jk.jam_mulai,
    k.nama_kelas
");

?>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="bi bi-calendar-week"></i>
                Data Jadwal Kuliah
            </h4>

            <a href="tambah.php" class="btn btn-light">
                <i class="bi bi-plus-circle"></i>
                Tambah Jadwal
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-primary text-center">

                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        if(mysqli_num_rows($query)>0){

                            $no=1;

                            while($row=mysqli_fetch_assoc($query)){

                        ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td><?= $row['nama_hari']; ?></td>

                            <td>
                                <?= substr($row['jam_mulai'],0,5); ?>
                                -
                                <?= substr($row['jam_selesai'],0,5); ?>
                            </td>

                            <td><?= $row['nama_kelas']; ?></td>

                            <td><?= $row['kode_mk']; ?></td>

                            <td><?= $row['nama_mk']; ?></td>

                            <td><?= $row['nama_dosen']; ?></td>

                            <td><?= $row['nama_ruangan']; ?></td>

                            <td class="text-center">

                                <a href="edit.php?id=<?= $row['id_jadwal']; ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="hapus.php?id=<?= $row['id_jadwal']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus jadwal?')">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                        <?php

                            }

                        }else{

                        ?>

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada data jadwal.

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