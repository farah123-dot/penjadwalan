<?php

session_start();

if (isset($_SESSION['login'])) {

    header("Location: ../index.php");

    exit;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Register Dosen - SIKULIAH</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- Bootstrap Icons -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">


<!-- CSS LOGIN & REGISTER -->

<link
href="login.css"
rel="stylesheet">

</head>


<body>


<div class="register-card">


    <div class="card-body px-4 py-4">


        <!-- =========================
             LOGO
        ========================= -->

        <div class="text-center mb-4">

            <div class="login-logo">

                <i class="bi bi-mortarboard-fill"></i>

                SIKULIAH

            </div>


            <div class="login-subtitle">

                Registrasi Akun Dosen

            </div>

        </div>



        <!-- =========================
             FORM REGISTER
        ========================= -->

        <form
            action="proses_register.php"
            method="POST"
            autocomplete="off"
        >


            <!-- NAMA DOSEN -->

            <div class="mb-3">

                <label class="form-label">

                    Nama Lengkap Dosen

                </label>


                <input
                    type="text"
                    name="nama_dosen"
                    class="form-control"
                    autocomplete="off"
                    required
                >

            </div>



            <!-- USERNAME -->

            <div class="mb-3">

                <label class="form-label">

                    Username

                </label>


                <input
                    type="text"
                    name="username"
                    class="form-control"
                    autocomplete="off"
                    required
                >

            </div>



            <!-- PASSWORD -->

            <div class="mb-3">

                <label class="form-label">

                    Password

                </label>


                <input
                    type="password"
                    name="password"
                    class="form-control"
                    autocomplete="new-password"
                    required
                >

            </div>



            <!-- KONFIRMASI PASSWORD -->

            <div class="mb-4">

                <label class="form-label">

                    Konfirmasi Password

                </label>


                <input
                    type="password"
                    name="password_confirm"
                    class="form-control"
                    autocomplete="new-password"
                    required
                >

            </div>



            <!-- =========================
                 REGISTER BUTTON
            ========================= -->

            <button
                type="submit"
                class="btn-register w-100"
            >

                <i class="bi bi-person-plus"></i>

                Daftar sebagai Dosen

            </button>


        </form>



        <!-- =========================
             LOGIN
        ========================= -->

        <div class="text-center mt-4">


            <div class="register-text">

                Sudah mempunyai akun?

            </div>


            <a
                href="login.php"
                class="btn-login w-100"
            >

                <i class="bi bi-box-arrow-in-right"></i>

                Login

            </a>


        </div>


    </div>

</div>


</body>

</html>
