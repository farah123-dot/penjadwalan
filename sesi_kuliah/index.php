<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";


/*
==================================================
AMBIL DATA SESI KULIAH
==================================================
*/

$query = mysqli_query($conn, "
    SELECT
        id_jam,
        jam_mulai,
        jam_selesai
    FROM jam_kuliah
    ORDER BY id_jam ASC
");

?>

<div class="container-fluid">

    <div class="card shadow">

        <!-- =====================================
             HEADER
        ====================================== -->

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                <i class="bi bi-clock"></i>

                Data Sesi Kuliah

            </h4>


            <a
                href="tambah.php"
                class="btn btn-light"
            >

                <i class="bi bi-plus-circle"></i>

                Tambah Sesi

            </a>

        </div>


        <!-- =====================================
             BODY
        ====================================== -->

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-primary text-center">

                        <tr>

                            <th width="80">
                                No
                            </th>

                            <th>
                                Sesi
                            </th>

                            <th>
                                Jam Mulai
                            </th>

                            <th>
                                Jam Selesai
                            </th>

                            <th width="150">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        if ($query && mysqli_num_rows($query) > 0) {

                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) {

                        ?>

                                <tr>

                                    <!-- NO -->

                                    <td class="text-center">

                                        <?= $no++; ?>

                                    </td>


                                    <!-- SESI -->

                                    <td class="text-center">

                                        <strong>
                                            Sesi <?= $row['id_jam']; ?>
                                        </strong>

                                    </td>


                                    <!-- JAM MULAI -->

                                    <td class="text-center">

                                        <?= substr($row['jam_mulai'], 0, 5); ?>

                                    </td>


                                    <!-- JAM SELESAI -->

                                    <td class="text-center">

                                        <?= substr($row['jam_selesai'], 0, 5); ?>

                                    </td>


                                    <!-- AKSI -->

                                    <td class="text-center">

                                        <a
                                            href="edit.php?id=<?= $row['id_jam']; ?>"
                                            class="btn btn-warning btn-sm"
                                            title="Edit"
                                        >

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <a
                                            href="hapus.php?id=<?= $row['id_jam']; ?>"
                                            class="btn btn-danger btn-sm"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus sesi kuliah ini?')"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </a>

                                    </td>

                                </tr>

                        <?php

                            }

                        } else {

                        ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >

                                    Belum ada data sesi kuliah.

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
