<?php

require_once __DIR__ . "/../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
AMBIL ID JADWAL
==================================================
*/

$id = $_GET['id'] ?? '';

if ($id == '') {

    header("Location:index.php");
    exit;

}


/*
==================================================
AMBIL DATA JADWAL
==================================================
*/

$query_data = mysqli_query($conn, "
    SELECT
        j.*,
        dm.id_dosen,
        dm.id_mk,
        dm.id_kelas
    FROM jadwal j
    JOIN dosen_mk dm
        ON j.id_dosen_mk = dm.id
    WHERE j.id_jadwal = '$id'
    LIMIT 1
");


$data = mysqli_fetch_assoc($query_data);


if (!$data) {

    echo "
    <script>

        alert('Data jadwal tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
DATA HARI
==================================================
*/

$hari = mysqli_query($conn, "
    SELECT *
    FROM hari
    ORDER BY id_hari
");


/*
==================================================
DATA JAM / SESI
==================================================
*/

$jam = mysqli_query($conn, "
    SELECT *
    FROM jam_kuliah
    ORDER BY id_jam
");


/*
==================================================
DATA RUANGAN
==================================================
*/

$ruangan = mysqli_query($conn, "
    SELECT *
    FROM ruangan
    ORDER BY nama_ruangan
");


/*
==================================================
DATA DOSEN
==================================================
*/

$dosen = mysqli_query($conn, "
    SELECT *
    FROM dosen
    ORDER BY nama_dosen
");


/*
==================================================
DATA KELAS + MATA KULIAH
==================================================
*/

$kelas_mk = mysqli_query($conn, "
    SELECT
        dm.id,
        dm.id_kelas,
        dm.id_mk,
        k.nama_kelas,
        mk.kode_mk,
        mk.nama_mk

    FROM dosen_mk dm

    JOIN kelas k
        ON dm.id_kelas = k.id_kelas

    JOIN mata_kuliah mk
        ON dm.id_mk = mk.id_mk

    ORDER BY
        k.nama_kelas,
        mk.nama_mk
");


include "../layout/header.php";
include "../layout/sidebar.php";

?>


<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4 class="mb-0">

                <i class="bi bi-pencil-square"></i>

                Edit Jadwal Kuliah

            </h4>

        </div>


        <div class="card-body">

            <form action="update.php" method="POST">


                <!-- ID JADWAL -->

                <input
                    type="hidden"
                    name="id_jadwal"
                    value="<?= $data['id_jadwal']; ?>"
                >


                <!-- HARI -->

                <div class="mb-3">

                    <label class="form-label">

                        Hari

                    </label>

                    <select
                        name="id_hari"
                        class="form-select"
                        required
                    >

                        <?php while($h = mysqli_fetch_assoc($hari)){ ?>

                            <option
                                value="<?= $h['id_hari']; ?>"
                                <?= ($data['id_hari'] == $h['id_hari']) ? 'selected' : ''; ?>
                            >

                                <?= $h['nama_hari']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- SESI -->

                <div class="mb-3">

                    <label class="form-label">

                        Sesi

                    </label>

                    <select
                        name="id_jam"
                        class="form-select"
                        required
                    >

                        <?php

                        $no_sesi = 1;

                        while($j = mysqli_fetch_assoc($jam)){

                        ?>

                            <option
                                value="<?= $j['id_jam']; ?>"
                                <?= ($data['id_jam'] == $j['id_jam']) ? 'selected' : ''; ?>
                            >

                                Sesi <?= $no_sesi; ?>

                                -
                                
                                <?= substr($j['jam_mulai'],0,5); ?>

                                -

                                <?= substr($j['jam_selesai'],0,5); ?>

                            </option>

                        <?php

                            $no_sesi++;

                        }

                        ?>

                    </select>

                </div>


                <!-- KELAS / MATA KULIAH -->

                <div class="mb-3">

                    <label class="form-label">

                        Kelas / Mata Kuliah

                    </label>

                    <select
                        name="id_kelas_mk"
                        class="form-select"
                        required
                    >

                        <?php while($km = mysqli_fetch_assoc($kelas_mk)){ ?>

                            <option
                                value="<?= $km['id']; ?>"
                                <?= (
                                    $data['id_kelas'] == $km['id_kelas']
                                    &&
                                    $data['id_mk'] == $km['id_mk']
                                ) ? 'selected' : ''; ?>
                            >

                                <?= $km['nama_kelas']; ?>

                                |

                                <?= $km['kode_mk']; ?>

                                -

                                <?= $km['nama_mk']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- DOSEN -->

                <div class="mb-3">

                    <label class="form-label">

                        Dosen Mengajar

                    </label>

                    <select
                        name="id_dosen"
                        class="form-select"
                        required
                    >

                        <?php while($d = mysqli_fetch_assoc($dosen)){ ?>

                            <option
                                value="<?= $d['id_dosen']; ?>"
                                <?= ($data['id_dosen'] == $d['id_dosen']) ? 'selected' : ''; ?>
                            >

                                <?= $d['nama_dosen']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- RUANGAN -->

                <div class="mb-3">

                    <label class="form-label">

                        Ruangan

                    </label>

                    <select
                        name="id_ruangan"
                        class="form-select"
                        required
                    >

                        <?php while($r = mysqli_fetch_assoc($ruangan)){ ?>

                            <option
                                value="<?= $r['id_ruangan']; ?>"
                                <?= ($data['id_ruangan'] == $r['id_ruangan']) ? 'selected' : ''; ?>
                            >

                                <?= $r['nama_ruangan']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- TOMBOL -->

                <button
                    type="submit"
                    class="btn btn-warning"
                >

                    <i class="bi bi-save"></i>

                    Update

                </button>


                <a
                    href="index.php"
                    class="btn btn-secondary"
                >

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>


<?php

include "../layout/footer.php";

?>
