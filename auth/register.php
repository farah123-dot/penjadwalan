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

body {

    background: #0d6efd;

    display: flex;

    justify-content: center;

    align-items: center;

    min-height: 100vh;

    padding: 20px;

}

.card {

    width: 450px;

    border: none;

    border-radius: 15px;

}

</style>

</head>

<body>

<div class="card shadow">

<div class="card-body p-4">


<div class="text-center mb-4">

<h2>

<i class="bi bi-mortarboard-fill"></i>

SIKULIAH

</h2>

<small class="text-muted">

Registrasi Akun Dosen

</small>

</div>


<form action="proses_register.php" method="POST">


<!-- NAMA DOSEN -->

<div class="mb-3">

<label class="form-label">

Nama Lengkap Dosen

</label>

<input
type="text"
name="nama_dosen"
class="form-control"
placeholder="Masukkan nama lengkap"
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
placeholder="Buat username"
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
placeholder="Buat password"
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
placeholder="Ulangi password"
required
>

</div>


<button
type="submit"
class="btn btn-primary w-100">

<i class="bi bi-person-plus"></i>

Daftar sebagai Dosen

</button>


</form>


<div class="text-center mt-4">

<small class="text-muted">

Sudah mempunyai akun?

</small>

<br>

<a
href="login.php"
class="text-decoration-none fw-semibold">

<i class="bi bi-box-arrow-in-right"></i>

Login

</a>

</div>


</div>

</div>

</body>

</html>
