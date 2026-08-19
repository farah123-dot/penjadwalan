<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";


/* ===============================
   AMBIL DATA KELAS DIBUKA
================================ */

$query = mysqli_query($conn, "

    SELECT
        kd.id_kelas_dibuka,
        k.nama_kelas,
        mk.kode_mk,
        mk.nama_mk,
        mk.semester

    FROM kelas_dibuka kd

    INNER JOIN kelas k
        ON kd.id_kelas = k.id_kelas

    INNER JOIN mata_kuliah mk
        ON kd.id_mk = mk.id_mk

    ORDER BY
        mk.semester ASC,
        k.nama_kelas ASC,
        mk.nama_mk ASC

");

?>

<style>

.page-container {
    padding: 25px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-header h3 {
    margin: 0;
    color: #1e293b;
}

.btn-tambah {
    background: #2563eb;
    color: white;
    padding: 9px 15px;
    border-radius: 7px;
    text-decoration: none;
}

.btn-tambah:hover {
    background: #1d4ed8;
    color: white;
}

.table-box {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #1e293b;
    color: white;
    padding: 12px;
    text-align: center;
}

td {
    padding: 11px;
    border: 1px solid #e5e7eb;
}

.text-center {
    text-align: center;
}

.btn-edit {
    background: #f59e0b;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.btn-hapus {
    background: #dc2626;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}

</style>


<div class="page-container">

    <div class="page-header">

        <h3>
            📚 Data Kelas Dibuka
        </h3>

        <a href="tambah.php" class="btn-tambah">
            + Tambah Kelas Dibuka
        </a>

    </div>


    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>No</th>
                    <th>Kelas</th>
                    <th>Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th>Semester</th>
                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

                <?php

                if (mysqli_num_rows($query) > 0) {

                    $no = 1;

                    while ($row = mysqli_fetch_assoc($query)) {

                ?>

                <tr>

                    <td class="text-center">
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_kelas']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['kode_mk']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama_mk']); ?>
                    </td>

                    <td class="text-center">
                        <?= htmlspecialchars($row['semester']); ?>
                    </td>

                    <td class="text-center">

                        <a
                            href="edit.php?id=<?= $row['id_kelas_dibuka']; ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="hapus.php?id=<?= $row['id_kelas_dibuka']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus kelas dibuka ini?')"
                        >
                            Hapus
                        </a>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada kelas yang dibuka.

                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>
