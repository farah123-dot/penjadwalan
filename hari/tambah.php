<?php

require_once "../koneksi.php";
require_once "../auth/cek_login.php";

include "../layout/header.php";
include "../layout/sidebar_admin.php";

?>

<style>

.page-container {
    padding: 25px;
}

.form-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    max-width: 600px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    color: #334155;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
}

.btn-simpan {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 17px;
    border-radius: 7px;
}

.btn-kembali {
    background: #64748b;
    color: white;
    padding: 10px 17px;
    border-radius: 7px;
    text-decoration: none;
    margin-left: 5px;
}

</style>


<div class="page-container">

    <div class="form-box">

        <h3>➕ Tambah Hari</h3>

        <hr>

        <form action="simpan.php" method="POST">

            <div class="form-group">

                <label>
                    Nama Hari
                </label>

                <input
                    type="text"
                    name="nama_hari"
                    placeholder="Contoh: Senin"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn-simpan"
            >
                💾 Simpan
            </button>


            <a
                href="index.php"
                class="btn-kembali"
            >
                Kembali
            </a>

        </form>

    </div>

</div>


<?php

include "../layout/footer.php";

?>
