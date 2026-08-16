```php
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

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">


<style>

/* =========================
   BODY
========================= */

body {

    background: #ffffff;

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 20px;

}


/* =========================
   REGISTER CARD
========================= */

.register-card {

    width: 500px;

    background: #ffffff;

    border: none;

    border-radius: 15px;

    box-shadow:
        0 10px 30px rgba(0,0,0,.15);

}


/* =========================
   LOGO
========================= */

.register-logo {

    font-size: 34px;

    font-weight: 700;

    color: #111827;

}


.register-logo i {

    margin-right: 8px;

}


/* =========================
   SUBTITLE
========================= */

.register-subtitle {

    margin-top: 10px;

    font-size: 16px;

    color: #6c757d;

}


/* =========================
   LABEL
========================= */

.register-card .form-label {

    font-size: 17px;

    font-weight: 500;

    color: #212529;

}


/* =========================
   INPUT
========================= */

.register-card .form-control {

    height: 48px;

    border-radius: 8px;

    font-size: 16px;

    border: 1px solid #ced4da;

}


.register-card .form-control:focus {

    border-color: #0d6efd;

    box-shadow:
        0 0 0 .2rem rgba(13,110,253,.15);

}


/* =========================
   REGISTER BUTTON
========================= */

.btn-register {

    height: 48px;

    border-radius: 8px;

    border: none;

    background: #0d6efd;

    color: #ffffff !important;

    font-size: 17px;

    font-weight: 500;

    cursor: pointer;

    box-shadow:
        0 4px 0 #0a58ca,
        0 7px 14px rgba(13,110,253,.25);

    transition: all .15s ease;

}


.btn-register:hover {

    background: #0b5ed7;

    color: #ffffff !important;

    transform: translateY(-2px);

    box-shadow:
        0 6px 0 #0a58ca,
        0 9px 18px rgba(13,110,253,.30);

}


.btn-register:active {

    transform: translateY(3px);

    box-shadow:
        0 1px 0 #0a58ca,
        0 3px 6px rgba(13,110,253,.20);

}


/* =========================
   LOGIN TEXT
========================= */

.login-text {

    color: #6c757d;

    font-size: 15px;

    margin-bottom: 10px;

}


/* =========================
   LOGIN BUTTON
========================= */

.btn-login {

    height: 48px;

    border-radius: 8px;

    background: #0d6efd;

    color: #ffffff !important;

    text-decoration: none;

    font-size: 16px;

    font-weight: 500;

    display: flex;

    justify-content: center;

    align-items: center;

    gap: 8px;

    box-shadow:
        0 4px 0 #0a58ca,
        0 7px 14px rgba(13,110,253,.25);

    transition: all .15s ease;

}


.btn-login:hover {

    background: #0b5ed7;

    color: #ffffff !important;

    transform: translateY(-2px);

    box-shadow:
        0 6px 0 #0a58ca,
        0 9px 18px rgba(13,110,253,.30);

}


.btn-login:active {

    transform: translateY(3px);

    box-shadow:
        0 1px 0 #0a58ca,
        0 3px 6px rgba(13,110,253,.20);

}


/* =========================
   MOBILE
========================= */

@media (max-width: 576px) {

    .register-card {

        width: 100%;

    }


    .register-logo {

        font-size: 30px;

    }

}

</style>

</head>


<body>


<div class="register-card">


<div class="card-body p-4">


<!-- ===============================
     LOGO
================================ -->

<div class="text-center mb-4">

    <div class="register-logo">

        <i class="bi bi-mortarboard-fill"></i>

        SIKULIAH

    </div>

    <div class="register-subtitle">

        Registrasi Akun Dosen

    </div>

</div>



<!-- ===============================
     FORM REGISTER
================================ -->

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
        value=""
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
        value=""
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
        value=""
        required
    >

</div>



<!-- KONFIRMASI PASSWORD -->

<div class="mb-3">

    <label class="form-label">

        Konfirmasi Password

    </label>

    <input
        type="password"
        name="password_confirm"
        class="form-control"
        autocomplete="new-password"
        value=""
        required
    >

</div>



<!-- ===============================
     BUTTON REGISTER
================================ -->

<button
    type="submit"
    class="btn-register w-100"
>

    <i class="bi bi-person-plus"></i>

    Daftar sebagai Dosen

</button>


</form>



<!-- ===============================
     LOGIN
================================ -->

<div class="text-center mt-4">

    <div class="login-text">

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
```
