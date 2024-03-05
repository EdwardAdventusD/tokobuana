<?php
require 'backend/function.php';
require 'backend/auth.php';

if ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Kasir') {
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

$get = mysqli_query($conn, "SELECT tr.*, mb.id_member as id_member, mb.nama_member
    FROM transaksi tr 
    JOIN member mb
    ON tr.id_member=mb.id_member
    WHERE 1 $filterClause
    ORDER BY tr.id_transaksi DESC");

$total_harga_perbulan_query = mysqli_query($conn, "SELECT SUM(total_harga) AS total_harga_perbulan
    FROM transaksi tr 
    JOIN member mb ON tr.id_member=mb.id_member
    WHERE 1 $filterClause");
$total_harga_perbulan_row = mysqli_fetch_assoc($total_harga_perbulan_query);
$total_harga_perbulan = $total_harga_perbulan_row['total_harga_perbulan'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>APK Transaksi - Transaksi</title>
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
    <p class="fw-bold my-2">
<?php
echo "Total Harga Transaksi : ".number_format($total_harga_perbulan);
?>
    </p>
  </div>
        <div class="row mt-4">
          <div class="col-12">
          <button type="button" class="btn btn-secondary float-end" data-bs-toggle="modal" data-bs-target="#TransaksiAddModal">
            <i class="bi bi-plus-lg"></i>
                  </button>
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
          <button type="button" value="<?= $tr['id_transaksi'];?>" class="EditTransaksiBtn btn btn-warning"><i class="bi bi-pencil-square"></i></button>
          <button type="button" value="<?= $tr['id_transaksi'];?>" class="DeleteTransaksiBtn btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
          </div>
          </p>
          <hr />
          <?php } } ?>
        </div>
      </div>
    </div>

    <!-- Modal Edit -->
 <div class="modal fade" id="TransaksiEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Transaksi</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
          <form id="UpdateTransaksi">
          <!-- <form method="post"> -->
          <div id="errorMessageUpdate" class="alert alert-danger d-none"></div>
          <label for="nama_member" class="form-label">Nama Pelanggan</label>
          <select class="form-select" name="id_member" id="view_id_member" required>
          <option value="" disabled selected>Pilih Pelanggan</option>
            <?php
            $get = mysqli_query($conn, 'SELECT * FROM member ORDER BY nama_member');
            while ($mb = mysqli_fetch_array($get)) {
              $id_member = $mb['id_member'];
              $nama_member = $mb['nama_member'];
              echo "<option value='$id_member'>$nama_member</option>";
            }
            ?>
          </select>
            <label for="total_harga" class="form-label mt-1">Total Harga</label>
              <input type="number" class="form-control" placeholder="Total Harga" name="total_harga" autocomplete="off" id="view_total_harga" required/>
            <label for="tanggal_transaksi" class="form-label mt-1">Tanggal Transaksi</label>
              <input type="date" class="form-control" name="tanggal_transaksi" id="view_tanggal_transaksi" required/>
              <label for="status" class="form-label mt-1">Status</label>
          <select name="status" class="form-select" id="view_status" required>
            <option value="" disabled selected>Pilih Status</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum Lunas">Belum Lunas</option>
          </select>
          <label for="tanggal_bayar" class="form-label mt-1">Tanggal Bayar</label>
              <input type="date" class="form-control" name="tanggal_bayar" id="view_tanggal_bayar" />
            <label for="keterangan" class="form-label mt-1">Keterangan</label>
              <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" autocomplete="off" id="view_keterangan" />
              <input type="hidden" name="id_transaksi" id="id_transaksi"/>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-success">Edit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
        </div>
      </div>
    </div>

<!-- Modal Insert -->
<div class="modal fade" id="TransaksiAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Transaksi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
      <form id="SaveTransaksi">
      <!-- <form method="post"> -->
          <label for="nama_member" class="form-label">Nama Pelanggan</label>
          <select class="form-select" name="id_member" required>
          <option value="" disabled selected>Pilih Pelanggan</option>
            <?php
            $get = mysqli_query($conn, 'SELECT * FROM member ORDER BY nama_member');
            while ($mb = mysqli_fetch_array($get)) {
              $id_member = $mb['id_member'];
              $nama_member = $mb['nama_member'];
              echo "<option value='$id_member'>$nama_member</option>";
            }
            ?>
          </select>
          <label for="total_harga" class="form-label mt-1">Total Harga</label>
          <input type="number" class="form-control" placeholder="Total Harga" name="total_harga" autocomplete="off" required/>
          <label for="tanggal_transaksi" class="form-label mt-1">Tanggal Transaksi</label>
          <input type="date" class="form-control" name="tanggal_transaksi" required/>
          <label for="status" class="form-label mt-1">Status</label>
          <select name="status" class="form-select" required>
            <option value="" disabled selected>Pilih Status</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum Lunas">Belum Lunas</option>
          </select>
          <label for="nama_toko" class="form-label mt-1">Toko</label>
          <select class="form-select" name="id_toko" required>
            <?php
            $get = mysqli_query($conn, 'SELECT * FROM toko ORDER BY nama_toko');
            while ($mb = mysqli_fetch_array($get)) {
              $id_toko = $mb['id_toko'];
              $nama_toko = $mb['nama_toko'];
              echo "<option value='$id_toko'>$nama_toko</option>";
            }
            ?>
          </select>
          <label for="keterangan" class="form-label mt-1">Keterangan</label>
          <input type="text" class="form-control" placeholder="Keterangan" name="keterangan" autocomplete="off" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="assets/script.js"></script>
  </body>
</html>
