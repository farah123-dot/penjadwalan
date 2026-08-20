<?php

require_once "koneksi.php";
require_once "auth/cek_login.php";

include "layout/header.php";
include "layout/sidebar_dosen.php";


/*
==================================================
AMBIL ID DOSEN DARI LOGIN
==================================================
*/

/*
   Sesuaikan dengan session login kamu.

   Kalau saat login kamu menyimpan:
   $_SESSION['id_dosen']

   maka kode ini langsung bisa digunakan.
*/

$id_dosen = $_SESSION['id_dosen'] ?? 0;


/*
==================================================
DATA DOSEN
==================================================
*/

$data_dosen = null;

if ($id_dosen != 0) {

    $query_dosen = mysqli_query($conn, "
        SELECT
            id_dosen,
            nama_dosen
        FROM dosen
        WHERE id_dosen = '$id_dosen'
        LIMIT 1
    ");

    $data_dosen = mysqli_fetch_assoc($query_dosen);

}


/*
==================================================
NAMA DOSEN
==================================================
*/

$nama_dosen = $data_dosen['nama_dosen'] ?? 'Dosen';


/*
==================================================
JUMLAH JADWAL DOSEN
==================================================
*/

$jumlah_jadwal = 0;

if ($id_dosen != 0) {

    $query_jumlah = mysqli_query($conn, "

        SELECT COUNT(*) AS total

        FROM jadwal j

        JOIN dosen_mk dm
            ON j.id_dosen_mk = dm.id

        WHERE dm.id_dosen = '$id_dosen'

    ");

    if ($query_jumlah) {

        $hasil = mysqli_fetch_assoc($query_jumlah);

        $jumlah_jadwal = $hasil['total'];

    }

}


/*
==================================================
JADWAL DOSEN
==================================================
*/

$query_jadwal = mysqli_query($conn, "

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

    JOIN hari h
        ON j.id_hari = h.id_hari

    JOIN jam_kuliah jk
        ON j.id_jam = jk.id_jam

    JOIN ruangan r
        ON j.id_ruangan = r.id_ruangan

    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id

    JOIN kelas k
        ON dm.id_kelas = k.id_kelas

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    WHERE dm.id_dosen = '$id_dosen'

    ORDER BY
        h.id_hari ASC,
        jk.jam_mulai ASC

");

?>


<style>

/* =========================================
   DASHBOARD DOSEN
========================================= */

.dashboard-dosen {

    padding: 25px;

    width: 100%;

    box-sizing: border-box;

}


/* =========================================
   HEADER
========================================= */

.dashboard-dosen-header {

    margin-bottom: 25px;

}

.dashboard-dosen-header h2 {

    margin: 0;

    font-size: 26px;

    font-weight: 700;

    color: #1e293b;

}

.dashboard-dosen-header p {

    margin-top: 7px;

    color: #64748b;

    font-size: 14px;

}


/* =========================================
   CARD
========================================= */

.dosen-card {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    padding: 22px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    display: flex;

    align-items: center;

    gap: 18px;

    margin-bottom: 25px;

    max-width: 350px;

}

.dosen-card-icon {

    width: 55px;

    height: 55px;

    border-radius: 12px;

    background: #dbeafe;

    color: #2563eb;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 25px;

}

.dosen-card-label {

    color: #64748b;

    font-size: 13px;

}

.dosen-card-number {

    color: #1e293b;

    font-size: 25px;

    font-weight: 700;

}


/* =========================================
   JADWAL
========================================= */

.jadwal-dosen-box {

    background: white;

    border: 1px solid #e5e7eb;

    border-radius: 14px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.07);

    overflow: hidden;

}

.jadwal-dosen-header {

    padding: 18px 20px;

    border-bottom: 1px solid #e5e7eb;

}

.jadwal-dosen-header h3 {

    margin: 0;

    font-size: 18px;

    color: #1e293b;

}

.jadwal-dosen-body {

    padding: 20px;

    overflow-x: auto;

}

.jadwal-dosen-table {

    width: 100%;

    border-collapse: collapse;

    min-width: 800px;

    font-size: 13px;

}

.jadwal-dosen-table th {

    background: #1e293b;

    color: white;

    padding: 12px;

    border: 1px solid #334155;

}

.jadwal-dosen-table td {

    padding: 11px;

    border: 1px solid #e5e7eb;

    text-align: center;

}

.jadwal-dosen-table tbody tr:hover {

    background: #f8fafc;

}

.tidak-ada {

    text-align: center;

    padding: 30px !important;

    color: #64748b;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 768px) {

    .dashboard-dosen {

        padding: 15px;

    }

    .dashboard-dosen-header h2 {

        font-size: 22px;

    }

}

</style>


<div class="dashboard-dosen">


    <!-- =====================================
         HEADER
    ====================================== -->

    <div class="dashboard-dosen-header">

        <h2>
            Dashboard Dosen
        </h2>

        <p>
            Selamat datang, <strong><?= htmlspecialchars($nama_dosen); ?></strong>.
            Berikut jadwal perkuliahan Anda.
        </p>

    </div>


    <!-- =====================================
         CARD JUMLAH JADWAL
    ====================================== -->

    <div class="dosen-card">

        <div class="dosen-card-icon">

            📅

        </div>

        <div>

            <div class="dosen-card-label">

                Jumlah Jadwal Mengajar

            </div>

            <div class="dosen-card-number">

                <?= $jumlah_jadwal; ?>

            </div>

        </div>

    </div>


    <!-- =====================================
         JADWAL DOSEN
    ====================================== -->

    <div class="jadwal-dosen-box">


        <div class="jadwal-dosen-header">

            <h3>

                Jadwal Perkuliahan Saya

            </h3>

        </div>


        <div class="jadwal-dosen-body">


            <table class="jadwal-dosen-table">


                <thead>

                    <tr>

                        <th>No</th>

                        <th>Hari</th>

                        <th>Jam</th>

                        <th>Kelas</th>

                        <th>Kode MK</th>

                        <th>Mata Kuliah</th>

                        <th>Ruangan</th>

                    </tr>

                </thead>


                <tbody>

                    <?php

                    if ($query_jadwal && mysqli_num_rows($query_jadwal) > 0) {

                        $no = 1;

                        while ($row = mysqli_fetch_assoc($query_jadwal)) {

                    ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_hari']); ?>
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
                                    <?= htmlspecialchars($row['nama_mk']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_ruangan']); ?>
                                </td>

                            </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="7"
                                class="tidak-ada"
                            >

                                Belum ada jadwal mengajar.

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


<?php

include "layout/footer.php";

?>
