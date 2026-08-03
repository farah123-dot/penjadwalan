<?php
session_start();

if(isset($_SESSION['login'])){
    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Login</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<style>

body{

background:#0d6efd;
display:flex;
justify-content:center;
align-items:center;
height:100vh;

}

.card{

width:420px;
border:none;
border-radius:15px;

}

</style>

</head>

<body>

<div class="card shadow">

<div class="card-body p-4">

<h2 class="text-center mb-4">

<i class="bi bi-mortarboard-fill"></i>

SIKULIAH

</h2>

<form action="proses_login.php" method="POST">

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100">

Login

</button>

</form>

</div>

</div>

</body>

</html>