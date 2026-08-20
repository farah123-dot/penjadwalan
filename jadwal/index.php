<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";

?>


<div class="container-fluid">

    <div class="card shadow">

        <!-- =========================================
             HEADER
        ========================================== -->

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                <i class="bi bi-calendar-week"></i>

                Jadwal Kuliah

            </h4>


            <!-- ADMIN HANYA MELIHAT JADWAL -->

            <span class="badge bg-light text-primary">

                <i class="bi bi-eye"></i>

                Mode Lihat

            </span>

        </div>


        <!-- =========================================
             BODY
        ========================================== -->

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-primary text-center">

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Hari
                            </th>

                            <th>
                                Jam
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                Kode MK
                            </th>

                            <th>
                                Mata Kuliah
                            </th>

                            <th>
                                Dosen
                            </th>

                            <th>
                                Ruangan
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    /*
                    ==========================================
                    AMBIL DATA JADWAL
                    ==========================================
                    */

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

                            h.id_hari ASC,

                            jk.jam_mulai ASC,

                            k.nama_kelas ASC

                    ");


                    /*
                    ==========================================
                    CEK DATA
                    ==========================================
                    */

                    if ($query && mysqli_num_rows($query) > 0) {

                        $no = 1;


                        while ($row = mysqli_fetch_assoc($query)) {

                    ?>

                        <tr>


                            <!-- NO -->

                            <td class="text-center">

                                <?= $no++; ?>

                            </td>


                            <!-- HARI -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['nama_hari']
                                ); ?>

                            </td>


                            <!-- JAM -->

                            <td class="text-center">

                                <?= htmlspecialchars(
                                    substr($row['jam_mulai'], 0, 5)
                                ); ?>

                                -

                                <?= htmlspecialchars(
                                    substr($row['jam_selesai'], 0, 5)
                                ); ?>

                            </td>


                            <!-- KELAS -->

                            <td class="text-center">

                                <?= htmlspecialchars(
                                    $row['nama_kelas']
                                ); ?>

                            </td>


                            <!-- KODE MK -->

                            <td class="text-center">

                                <?= htmlspecialchars(
                                    $row['kode_mk']
                                ); ?>

                            </td>


                            <!-- MATA KULIAH -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['nama_mk']
                                ); ?>

                            </td>


                            <!-- DOSEN -->

                            <td>

                                <?= htmlspecialchars(
                                    $row['nama_dosen']
                                ); ?>

                            </td>


                            <!-- RUANGAN -->

                            <td class="text-center">

                                <?= htmlspecialchars(
                                    $row['nama_ruangan']
                                ); ?>

                            </td>


                        </tr>


                    <?php

                        }

                    } else {

                    ?>


                        <!-- TIDAK ADA DATA -->

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >

                                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                Belum ada jadwal kuliah.

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
