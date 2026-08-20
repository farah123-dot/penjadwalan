<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";


$query = mysqli_query($conn, "
    SELECT *
    FROM hari
    ORDER BY id_hari ASC
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
    margin-right: 5px;
}

.btn-edit:hover {
    color: white;
    background: #d97706;
}

.btn-hapus {
    background: #dc2626;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.btn-hapus:hover {
    color: white;
    background: #b91c1c;
}

</style>


<div class="page-container">

    <div class="page-header">

        <h3>
            📅 Data Hari
        </h3>

        <a href="tambah.php" class="btn-tambah">
            + Tambah Hari
        </a>

    </div>


    <div class="table-box">

        <table>

            <thead>

                <tr>
                    <th width="100">No</th>
                    <th>Nama Hari</th>
                    <th width="220">Aksi</th>
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
                        <?= htmlspecialchars($row['nama_hari']); ?>
                    </td>

                    <td class="text-center">

                        <a
                            href="edit.php?id=<?= $row['id_hari']; ?>"
                            class="btn-edit"
                        >
                            Edit
                        </a>

                        <a
                            href="hapus.php?id=<?= $row['id_hari']; ?>"
                            class="btn-hapus"
                            onclick="return confirm('Yakin ingin menghapus hari ini?')"
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

                    <td colspan="3" class="text-center">
                        Belum ada data hari.
                    </td>

                </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>


<?php

include "../layout/footer.php";

?>
