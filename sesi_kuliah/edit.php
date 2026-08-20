<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";


/*
==================================================
1. AMBIL ID SESI
==================================================
*/

$id = $_GET['id'] ?? '';


/*
==================================================
2. VALIDASI ID
==================================================
*/

if ($id == '') {

    echo "
    <script>

        alert('ID sesi kuliah tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


/*
==================================================
3. AMBIL DATA SESI
==================================================
*/

$query = mysqli_query($conn, "

    SELECT
        id_jam,
        jam_mulai,
        jam_selesai

    FROM jam_kuliah

    WHERE id_jam = '$id'

    LIMIT 1

");


$data = mysqli_fetch_assoc($query);


/*
==================================================
4. CEK DATA
==================================================
*/

if (!$data) {

    echo "
    <script>

        alert('Data sesi kuliah tidak ditemukan.');

        window.location='index.php';

    </script>
    ";

    exit;
}


include "../layout/header.php";
include "../layout/sidebar_admin.php";

?>


<div class="container-fluid">

    <div class="card shadow">


        <!-- =====================================
             HEADER
        ====================================== -->

        <div class="card-header bg-warning">

            <h4 class="mb-0">

                <i class="bi bi-pencil-square"></i>

                Edit Sesi Kuliah

            </h4>

        </div>


        <!-- =====================================
             BODY
        ====================================== -->

        <div class="card-body">

            <form action="update.php" method="POST">


                <!-- ID -->

                <input
                    type="hidden"
                    name="id_jam"
                    value="<?= $data['id_jam']; ?>"
                >


                <!-- =================================
                     JAM MULAI
                ================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Jam Mulai

                    </label>

                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="<?= substr($data['jam_mulai'], 0, 5); ?>"
                        required
                    >

                </div>


                <!-- =================================
                     JAM SELESAI
                ================================== -->

                <div class="mb-3">

                    <label class="form-label">

                        Jam Selesai

                    </label>

                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="<?= substr($data['jam_selesai'], 0, 5); ?>"
                        required
                    >

                </div>


                <!-- =================================
                     TOMBOL
                ================================== -->

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

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>


            </form>

        </div>

    </div>

</div>


<?php

include "../layout/footer.php";

?>
