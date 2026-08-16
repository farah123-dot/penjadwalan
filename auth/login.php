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

<title>Login - SIKULIAH</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">


<style>

body {

    background: #0d6efd;

    display: flex;

    justify-content: center;

    align-items: center;

    min-height: 100vh;

    padding: 20px;

}


.card {

    width: 500px;

    border: none;

    border-radius: 15px;

}


.card h2 {

    font-weight: 700;

}


.form-control {

    height: 48px;

    border-radius: 8px;

}


.btn {

    height: 48px;

    border-radius: 8px;

    font-weight: 500;

}

</style>

</head>


<body>


<div class="card shadow">


<div class="card-body p-4">


<!-- ===============================
     LOGO
================================ -->

<div class="text-center mb-4">

    <h2>

        <i class="bi bi-mortarboard-fill"></i>

        SIKULIAH

    </h2>

    <small class="text-muted">

        Sistem Penjadwalan Kuliah

    </small>

</div>



<!-- ===============================
     FORM LOGIN
================================ -->

<form
    action="proses_login.php"
    method="POST"
    autocomplete="off"
>


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



<!-- ===============================
     BUTTON LOGIN
================================ -->

<button
    type="submit"
    class="btn btn-primary w-100"
>

    <i class="bi bi-box-arrow-in-right"></i>

    Login

</button>


</form>



<!-- ===============================
     REGISTER
================================ -->

<div class="text-center mt-4">

    <small class="text-muted d-block mb-2">

        Belum mempunyai akun?

    </small>


    <a
        href="register.php"
        class="btn btn-primary w-100"
    >

        <i class="bi bi-person-plus"></i>

        Daftar sebagai Dosen

    </a>

</div>


</div>

</div>


</body>

</html>
