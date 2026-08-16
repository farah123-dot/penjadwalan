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


<!-- CSS KHUSUS LOGIN -->

<link
href="login.css"
rel="stylesheet">

</head>


<body>


<div class="login-card shadow">


<div class="card-body p-4 p-md-5">


<!-- ===============================
     LOGO
================================ -->

<div class="text-center mb-4">

    <div class="login-logo">

        <i class="bi bi-mortarboard-fill"></i>

        SIKULIAH

    </div>


    <div class="login-subtitle mt-3">

        Sistem Penjadwalan Kuliah

    </div>

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

<div class="mb-4">

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

<div class="mb-4">

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
    class="btn-login w-100"
>

    <i class="bi bi-box-arrow-in-right"></i>

    Login

</button>


</form>



<!-- ===============================
     REGISTER
================================ -->

<div class="text-center mt-4">


<div class="register-text mb-2">

    Belum mempunyai akun?

</div>


<a
    href="register.php"
    class="btn-register w-100"
>

    <i class="bi bi-person-plus me-2"></i>

    Daftar sebagai Dosen

</a>


</div>


</div>

</div>


</body>

</html>
