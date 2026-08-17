<?php

require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar.php";


/* ===============================
   FUNGSI HITUNG DATA
================================ */

function jumlahData($conn, $tabel)
{
    $query = mysqli_query($conn, "SELECT * FROM $tabel");

    if ($query) {
        return mysqli_num_rows($query);
    }

    return 0;
}


/* ===============================
   JUMLAH DATA
================================ */

$jumlah_dosen   = jumlahData($conn, "dosen");
$jumlah_mk      = jumlahData($conn, "mata_kuliah");
$jumlah_kelas   = jumlahData($conn, "kelas");
$jumlah_ruangan = jumlahData($conn, "ruangan");
$jumlah_jam     = jumlahData($conn, "jam_kuliah");
$jumlah_jadwal  = jumlahData($conn, "jadwal");


/* ===============================
   AMBIL DATA JADWAL
================================ */

$query_jadwal = mysqli_query($conn, "

SELECT 
    jadwal.id_jadwal,
    kelas.nama_kelas,
    hari.nama_hari,
    jam_kuliah.jam_mulai,
    jam_kuliah.jam_selesai,
    ruangan.nama_ruangan,
    dosen.nama_dosen,
    mata_kuliah.nama_mk

FROM jadwal

JOIN hari
ON jadwal.id_hari = hari.id_hari

JOIN jam_kuliah
ON jadwal.id_jam = jam_kuliah.id_jam

JOIN ruangan
ON jadwal.id_ruangan = ruangan.id_ruangan

JOIN dosen_mk
ON jadwal.id_dosen_mk = dosen_mk.id

JOIN dosen
ON dosen_mk.id_dosen = dosen.id_dosen

JOIN mata_kuliah
ON dosen_mk.id_mk = mata_kuliah.id_mk

JOIN kelas
ON dosen_mk.id_kelas = kelas.id_kelas

ORDER BY 
hari.id_hari ASC,
jam_kuliah.jam_mulai ASC

");
?>


<div class="content">

    <!-- ===============================
         NAVBAR
    ================================ -->

    <div class="navbar bg-white shadow-sm mb-4">

        <div>

            <h4 class="mb-0">
                Dashboard SIKULIAH
            </h4>

            <small>
                Sistem Penjadwalan Kuliah
            </small>

        </div>

    </div>


    <!-- ===============================
         CARD JUMLAH DATA
    ================================ -->

    <div class="row">


        <!-- DOSEN -->

        <div class="col-md-3">

            <div class="card card-dashboard shadow mb-3 bg1 text-white">

                <div class="card-body">

                    <i class="bi bi-person-badge float-end"></i>

                    <h2>
                        <?= $jumlah_dosen ?>
                    </h2>

                    <h5>
                        Data Dosen
                    </h5>

                    <small>
                        Total dosen
                    </small>

                </div>

            </div>

        </div>



        <!-- MATA KULIAH -->

        <div class="col-md-3">

            <div class="card card-dashboard shadow mb-3 bg2 text-white">

                <div class="card-body">

                    <i class="bi bi-book float-end"></i>

                    <h2>
                        <?= $jumlah_mk ?>
                    </h2>

                    <h5>
                        Mata Kuliah
                    </h5>

                    <small>
                        Total mata kuliah
                    </small>

                </div>

            </div>

        </div>



        <!-- KELAS -->

        <div class="col-md-3">

            <div class="card card-dashboard shadow mb-3 bg5 text-white">

                <div class="card-body">

                    <i class="bi bi-people float-end"></i>

                    <h2>
                        <?= $jumlah_kelas ?>
                    </h2>

                    <h5>
                        Kelas
                    </h5>

                    <small>
                        Total kelas
                    </small>

                </div>

            </div>

        </div>



        <!-- JADWAL -->

        <div class="col-md-3">

            <div class="card card-dashboard shadow mb-3 bg4 text-white">

                <div class="card-body">

                    <i class="bi bi-calendar-week float-end"></i>

                    <h2>
                        <?= $jumlah_jadwal ?>
                    </h2>

                    <h5>
                        Jadwal
                    </h5>

                    <small>
                        Total jadwal
                    </small>

                </div>

            </div>

        </div>


    </div>



    <!-- ===============================
         JADWAL PERKULIAHAN
    ================================ -->

    <div class="card shadow">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="bi bi-calendar3 text-primary"></i>

                Jadwal Perkuliahan

            </h5>


            <a
                href="export_jadwal.php"
                class="btn btn-danger btn-sm"
            >

                <i class="bi bi-file-earmark-pdf"></i>

                Export PDF

            </a>

        </div>


        <div class="card-body">


            <!-- ===============================
                 PEMBUNGKUS SCROLL TABEL
            ================================ -->

            <div class="jadwal-wrapper">


                <table class="table table-bordered text-center jadwal-table">


                    <!-- ===============================
                         HEADER TABEL
                    ================================ -->

                    <thead>


                        <tr>


                            <th class="kolom-hari">
                                Hari
                            </th>


                            <th class="kolom-waktu">
                                Waktu
                            </th>


                            <?php

                            $data_ruangan = mysqli_query($conn, "

                                SELECT *
                                FROM ruangan
                                ORDER BY id_ruangan ASC

                            ");

                            while ($ruangan = mysqli_fetch_assoc($data_ruangan)) {

                            ?>

                                <th>

                                    <?= htmlspecialchars(
                                        $ruangan['nama_ruangan']
                                    ); ?>

                                </th>

                            <?php

                            }

                            ?>


                        </tr>


                    </thead>



                    <!-- ===============================
                         BODY TABEL
                    ================================ -->

                    <tbody>


                    <?php


                    $data_hari = mysqli_query($conn, "

                        SELECT *
                        FROM hari
                        ORDER BY id_hari ASC

                    ");


                    while ($hari = mysqli_fetch_assoc($data_hari)) {


                        $data_jam = mysqli_query($conn, "

                            SELECT *
                            FROM jam_kuliah
                            ORDER BY id_jam ASC

                        ");


                        $jumlah_jam_hari = mysqli_num_rows($data_jam);

                        $first = true;


                        while ($jam = mysqli_fetch_assoc($data_jam)) {


                    ?>


                        <tr>


                            <!-- ===============================
                                 HARI
                            ================================ -->

                            <?php if ($first) { ?>


                                <td
                                    rowspan="<?= $jumlah_jam_hari ?>"
                                    class="fw-bold align-middle kolom-hari"
                                >

                                    <?= htmlspecialchars(
                                        $hari['nama_hari']
                                    ); ?>

                                </td>


                            <?php

                                $first = false;

                            }

                            ?>



                            <!-- ===============================
                                 WAKTU
                            ================================ -->

                            <td class="kolom-waktu">

                                <?= htmlspecialchars(
                                    $jam['jam_mulai']
                                ); ?>

                                -

                                <?= htmlspecialchars(
                                    $jam['jam_selesai']
                                ); ?>

                            </td>



                            <!-- ===============================
                                 RUANGAN
                            ================================ -->

                            <?php


                            $data_ruangan2 = mysqli_query($conn, "

                                SELECT *
                                FROM ruangan
                                ORDER BY id_ruangan ASC

                            ");


                            while ($ruang = mysqli_fetch_assoc($data_ruangan2)) {


                                $jadwal = mysqli_query($conn, "

                                    SELECT

                                        mata_kuliah.nama_mk,
                                        dosen.nama_dosen,
                                        kelas.nama_kelas

                                    FROM jadwal

                                    JOIN dosen_mk

                                    ON jadwal.id_dosen_mk =
                                       dosen_mk.id

                                    JOIN mata_kuliah

                                    ON dosen_mk.id_mk =
                                       mata_kuliah.id_mk

                                    JOIN dosen

                                    ON dosen_mk.id_dosen =
                                       dosen.id_dosen

                                    JOIN kelas

                                    ON dosen_mk.id_kelas =
                                       kelas.id_kelas

                                    WHERE

                                        jadwal.id_hari =
                                        '{$hari['id_hari']}'

                                    AND

                                        jadwal.id_jam =
                                        '{$jam['id_jam']}'

                                    AND

                                        jadwal.id_ruangan =
                                        '{$ruang['id_ruangan']}'

                                ");


                                if (mysqli_num_rows($jadwal) > 0) {


                                    $data = mysqli_fetch_assoc($jadwal);

                            ?>


                                    <td class="jadwal-terisi">


                                        <strong>

                                            <?= htmlspecialchars(
                                                $data['nama_mk']
                                            ); ?>

                                        </strong>


                                        <br>


                                        <small>

                                            <?= htmlspecialchars(
                                                $data['nama_dosen']
                                            ); ?>

                                        </small>


                                        <br>


                                        <small>

                                            <?= htmlspecialchars(
                                                $data['nama_kelas']
                                            ); ?>

                                        </small>


                                    </td>


                            <?php


                                } else {


                            ?>


                                    <td class="jadwal-kosong">

                                        -

                                    </td>


                            <?php

                                }

                            }


                            ?>


                        </tr>


                    <?php

                        }

                    }


                    ?>


                    </tbody>


                </table>


            </div>


        </div>

    </div>


</div>


<?php

include "layout/footer.php";

?>
