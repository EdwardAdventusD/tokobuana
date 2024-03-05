<?php
require 'backend/function.php';
require 'backend/auth.php';

$id_member = $_SESSION['id_member'];

$get_total_transaksi = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi");
$total_transaksi_row = mysqli_fetch_assoc($get_total_transaksi);
$total_transaksi = $total_transaksi_row['total'];

$get_total_pelanggan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM member");
$total_pelanggan_row = mysqli_fetch_assoc($get_total_pelanggan);
$total_pelanggan = $total_pelanggan_row['total'];

$get_total_pengguna = mysqli_query($conn, "SELECT COUNT(*) AS total FROM login");
$total_pengguna_row = mysqli_fetch_assoc($get_total_pengguna);
$total_pengguna = $total_pengguna_row['total'];

$get_total_toko = mysqli_query($conn, "SELECT COUNT(*) AS total FROM toko");
$total_toko_row = mysqli_fetch_assoc($get_total_toko);
$total_toko = $total_toko_row['total'];

$get_total_riwayat_transaksi = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi tr
    JOIN member mb ON tr.id_member = mb.id_member");

$total_riwayat_transaksi_row = mysqli_fetch_assoc($get_total_riwayat_transaksi);
$total_riwayat_transaksi = $total_riwayat_transaksi_row['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APK Transaksi - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container-fluid content">
    <div class="row">
        <div class="col-md-12">
            <p>
                Selamat Datang, <br>
                <span class="fw-bold txt-nama"><?php echo $_SESSION['username']; ?></span>
            </p>
        </div>
    </div>
    <div class="row text-center">
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Kasir') : ?>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="transaksi.php" class="p-2 card-index text-decoration-none"><i class="bi bi-cash-coin"></i> : <?php echo $total_transaksi; ?></a>
                </div>
                Transaksi
            </div>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="pelanggan.php" class="p-2 card-index text-decoration-none"><i class="bi bi-person-plus"></i> : <?php echo $total_pelanggan; ?></a>
                </div>
                Pelanggan
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'Admin') : ?>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="pengguna.php" class="p-2 card-index text-decoration-none"><i class="bi bi-person-gear"></i> : <?php echo $total_pengguna; ?></a>
                </div>
                Pengguna
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'Admin') : ?>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="toko.php" class="p-2 card-index text-decoration-none"><i class="bi bi-shop-window"></i> : <?php echo $total_toko; ?></a>
                </div>
                Toko
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Kasir') : ?>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="laporan.php" class="p-2 card-index text-decoration-none"><i class="bi bi-journal-text"></i> : <?php echo $total_pelanggan; ?></a>
                </div>
                Laporan
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'User') : ?>
            <div class="col-md-3 my-1">
                <div class="card bg-dark text-white">
                    <a href="riwayat_transaksi.php" class="p-2 card-index text-decoration-none"><i class="bi bi-clock-history"></i> : <?php echo $total_riwayat_transaksi; ?></a>
                </div>
                Riwayat Transaksi
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>