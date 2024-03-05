<?php
require 'backend/function.php';
require 'backend/auth.php';

if ($_SESSION['role'] != 'User') {
  header('Location: index.php');
  exit();
}

$i = 1;

$filterJenisTransaksi = isset($_GET['jenis_transaksi']) ? $_GET['jenis_transaksi'] : '';
$filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

$filterClause = '';
if ($filterJenisTransaksi) {
    $filterClause .= " AND tr.status = '$filterJenisTransaksi'";
}

if ($filterBulan) {
    $selectedMonth = date('m', strtotime($filterBulan));
    $filterClause .= " AND MONTH(tr.tanggal_transaksi) = '$selectedMonth'";
}

$id_member = $_SESSION['id_member'];

if ($id_member) {
    $filterClause .= " AND tr.id_member = '$id_member'";
}

if ($filterJenisTransaksi || $filterBulan || $id_member) {
    $get = mysqli_query($conn, "SELECT tr.*, mb.id_member as id_member, mb.nama_member
    FROM transaksi tr 
    JOIN member mb
    ON tr.id_member=mb.id_member
    WHERE 1 $filterClause
    ORDER BY tr.id_transaksi DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>APK Transaksi - Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style.css">
  </head>
  <body>
 <?php include 'nav.php'; ?>
<div class="container-fluid content">
  <div class="card shadow p-3">
    <form method="get">
      <div class="row">
      <div class="col-md-8">
          <label for="bulan">Data Pada Bulan :</label>
          <input type="month" class="form-control my-2" name="bulan" value="<?php echo $filterBulan; ?>" onchange="this.form.submit()">
        </div>
        <div class="col-md-4">
          <label for="jenis_transaksi">Jenis Transaksi :</label>
          <select name="jenis_transaksi" id="jenis_transaksi" class="form-select my-2" onchange="this.form.submit()">
            <option value="">- Semua Jenis Transaksi -</option>
            <option value="Lunas" <?php echo ($filterJenisTransaksi == 'Lunas') ? 'selected' : ''; ?>>Sudah Lunas</option>
            <option value="Belum Lunas" <?php echo ($filterJenisTransaksi == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
          </select>
        </div>
      </div>
    </form>
    <p class="text-danger">
      Catatan. <br>
      - Filter Terlebih Dahulu Agar Data Transaksi Muncul
    </p>
  </div>
        <div class="row mt-4">
          <div class="col-12">
          </div>
        <div class="col-12">
         <?php
                if ($filterJenisTransaksi || $filterBulan) {
                    while ($tr = mysqli_fetch_assoc($get)) {
                        $id_transaksi = $tr['id_transaksi'];
                        $tanggal_transaksi = $tr['tanggal_transaksi'];
                        $total_harga = $tr['total_harga'];
                        $nama_member = $tr['nama_member'];
                        $keterangan = $tr['keterangan'];
                        $status = $tr['status'];
                        ?>
          <h5 class="fw-bold <?= $status == 'Lunas' ? 'text-success' : 'text-danger'; ?>">
    <?= $tanggal_transaksi; ?>
</h5>
          <p>
          <?=number_format($total_harga);?><br />
          <?=$nama_member;?><br />
          <?=$status;?><br />
          <div class="mt-1 text-end">
          </div>
          </p>
          <hr />
          <?php } } ?>
        </div>
      </div>
    </div>
        </div>
      </div>
    </div>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="assets/script.js"></script>
  </body>
</html>
