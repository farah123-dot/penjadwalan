<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


$id = $_GET['id'] ?? '';


if ($id == '') {

    header("Location:index.php");
    exit;

}


/* ===============================
   DATA KELAS DIBUKA
================================ */

$data = mysqli_fetch_assoc(mysqli_query($conn, "

    SELECT *
    FROM kelas_dibuka

    WHERE id_kelas_dibuka = '$id'

    LIMIT 1

"));


if (!$data) {

    echo "
    <script>

        alert('Data kelas dibuka tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/* ===============================
   DATA KELAS
================================ */

$kelas = mysqli_query($conn, "

    SELECT *
    FROM kelas
    ORDER BY nama_kelas ASC

");


/* ===============================
   DATA MATA KULIAH
================================ */

$mata_kuliah = mysqli_query($conn, "

    SELECT *
    FROM mata_kuliah
    ORDER BY semester ASC, nama_mk ASC

");


include "../layout/header.php";
include "../layout/sidebar_admin.php";

?>

<div class="page-container">

    <div class="form-box">

        <h3>✏️ Edit Kelas Dibuka</h3>

        <hr>

        <form action="update.php" method="POST">


            <input
                type="hidden"
                name="id_kelas_dibuka"
                value="<?= $data['id_kelas_dibuka']; ?>"
            >


            <!-- KELAS -->

            <div class="form-group">

                <label>
                    Kelas
                </label>

                <select name="id_kelas" required>

                    <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>

                        <option
                            value="<?= $k['id_kelas']; ?>"
                            <?= $data['id_kelas'] == $k['id_kelas'] ? 'selected' : ''; ?>
                        >

                            <?= htmlspecialchars($k['nama_kelas']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- MATA KULIAH -->

            <div class="form-group">

                <label>
                    Mata Kuliah
                </label>

                <select name="id_mk" required>

                    <?php while ($mk = mysqli_fetch_assoc($mata_kuliah)) { ?>

                        <option
                            value="<?= $mk['id_mk']; ?>"
                            <?= $data['id_mk'] == $mk['id_mk'] ? 'selected' : ''; ?>
                        >

                            <?= htmlspecialchars($mk['kode_mk']); ?>
                            -
                            <?= htmlspecialchars($mk['nama_mk']); ?>
                            (Semester <?= htmlspecialchars($mk['semester']); ?>)

                        </option>

                    <?php } ?>

                </select>

            </div>


            <button type="submit" class="btn-simpan">
                💾 Update
            </button>

            <a href="index.php" class="btn-kembali">
                Kembali
            </a>

        </form>

    </div>

</div>


<?php
include "../layout/footer.php";
?>
