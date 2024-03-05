<?php
require_once 'backend/function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APK Transaksi - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-css">
    <div class="container-fluid content">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h1 class="fw-bold">APK Transaksi</h1>
                        <h4 class="fw-semibold">Toko Bu Ana</h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                        <input type="text" name="username" id="username" class="form-control shadow" placeholder="Enter Username" autocomplete="off" required>
                        <input type="password" name="password" id="password" class="form-control mt-1 shadow" placeholder="Enter Password" autocomplete="off" required>
                        <hr>
                        <button type="submit" class="btn btn-success float-end shadow" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>