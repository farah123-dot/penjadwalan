<?php

require_once __DIR__ . "/../koneksi.php";
require_once __DIR__ . "/../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_dosen.php";


/*
==================================================
AMBIL ID DOSEN YANG LOGIN
==================================================
*/

$id_dosen = $_SESSION['id_dosen'] ?? '';

$nama_dosen = $_SESSION['nama_dosen']
    ?? $_SESSION['nama']
    ?? $_SESSION['username']
    ?? 'Dosen';


/*
==================================================
JIKA ID DOSEN ADA
==================================================
*/

if ($id_dosen != '') {

    $data_dosen = mysqli_query($conn, "

        SELECT nama_dosen

        FROM dosen

        WHERE id_dosen = '$id_dosen'

        LIMIT 1

    ");

    if ($data_dosen && mysqli_num_rows($data_dosen) > 0) {

        $d = mysqli_fetch_assoc($data_dosen);

        $nama_dosen = $d['nama_dosen'];

    }

}


/*
==================================================
AMBIL JADWAL DOSEN
==================================================
*/

$query = false;

if ($id_dosen != '') {

    $query = mysqli_query($conn, "

        SELECT

            j.id_jadwal,

            h.nama_hari,

            jk.jam_mulai,

            jk.jam_selesai,

            r.nama_ruangan,

            k.nama_kelas,

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

        INNER JOIN mata_kuliah mk

            ON dm.id_mk = mk.id_mk

        INNER JOIN kelas k

            ON dm.id_kelas = k.id_kelas

        WHERE dm.id_dosen = '$id_dosen'

        ORDER BY

            h.id_hari ASC,

            jk.jam_mulai ASC

    ");

}

?>


<style>

/* =========================================
   HALAMAN JADWAL DOSEN
========================================= */

.jadwal-dosen-container {

    padding: 25px;

}


/* =========================================
   HEADER
========================================= */

.jadwal-dosen-header {

    margin-bottom: 20px;

}

.jadwal-dosen-header h2 {

    margin: 0;

    font-size: 25px;

    color: #1e293b;

}

.jadwal-dosen-header p {

    margin-top: 6px;

    color: #64748b;

}


/* =========================================
   CARD
========================================= */

.jadwal-card-box {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    overflow: hidden;

}


/* =========================================
   HEADER CARD
========================================= */

.jadwal-card-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}

.jadwal-card-header h3 {

    margin: 0;

    font-size: 18px;

    color: #1e293b;

}


/* =========================================
   SCROLL ATAS BAWAH + KIRI KANAN
========================================= */

.jadwal-scroll {

    max-height: 550px;

    overflow-x: auto;

    overflow-y: auto;

    padding: 20px;

}


/* =========================================
   TABLE
========================================= */

.jadwal-table {

    width: 100%;

    min-width: 900px;

    border-collapse: collapse;

    font-size: 13px;

}

.jadwal-table th {

    background: #1e293b;

    color: white;

    padding: 12px;

    border: 1px solid #334155;

    white-space: nowrap;

    position: sticky;

    top: 0;

    z-index: 5;

}

.jadwal-table td {

    border: 1px solid #e5e7eb;

    padding: 12px;

    text-align: center;

    vertical-align: middle;

}

.jadwal-table tr:hover {

    background: #f8fafc;

}


/* =========================================
   KETERANGAN MK
========================================= */

.mk-name {

    font-weight: 600;

    color: #1e293b;

}

.mk-code {

    font-size: 11px;

    color: #64748b;

}


/* =========================================
   EMPTY
========================================= */

.empty-data {

    padding: 40px;

    text-align: center;

    color: #64748b;

}


/* =========================================
   RESPONSIVE
========================================= */

@media(max-width:768px) {

    .jadwal-dosen-container {

        padding: 15px;

    }

}

</style>


<div class="jadwal-dosen-container">


    <!-- =====================================
         JUDUL
    ====================================== -->

    <div class="jadwal-dosen-header">

        <h2>

            Jadwal Saya

        </h2>

        <p>

            Jadwal perkuliahan
            <b><?= htmlspecialchars($nama_dosen); ?></b>

        </p>

    </div>


    <!-- =====================================
         CARD JADWAL
    ====================================== -->

    <div class="jadwal-card-box">


        <div class="jadwal-card-header">

            <h3>

                Jadwal Perkuliahan

            </h3>

        </div>


        <div class="jadwal-scroll">


            <?php if ($query && mysqli_num_rows($query) > 0) { ?>


                <table class="jadwal-table">


                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Hari</th>

                            <th>Waktu</th>

                            <th>Kelas</th>

                            <th>Kode MK</th>

                            <th>Mata Kuliah</th>

                            <th>Ruangan</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query)) {

                        ?>


                            <tr>


                                <td>

                                    <?= $no++; ?>

                                </td>


                                <td>

                                    <b>

                                        <?= htmlspecialchars($row['nama_hari']); ?>

                                    </b>

                                </td>


                                <td>

                                    <?= substr($row['jam_mulai'], 0, 5); ?>

                                    -

                                    <?= substr($row['jam_selesai'], 0, 5); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['nama_kelas']); ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['kode_mk']); ?>

                                </td>


                                <td>

                                    <div class="mk-name">

                                        <?= htmlspecialchars($row['nama_mk']); ?>

                                    </div>

                                </td>


                                <td>

                                    <?= htmlspecialchars($row['nama_ruangan']); ?>

                                </td>


                            </tr>


                        <?php } ?>


                    </tbody>


                </table>


            <?php } else { ?>


                <div class="empty-data">

                    Belum ada jadwal perkuliahan.

                </div>


            <?php } ?>


        </div>

    </div>


</div>


<?php

include "../layout/footer.php";

?>
