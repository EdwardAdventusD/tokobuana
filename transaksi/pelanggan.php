<?php
require 'backend/function.php';
require 'backend/auth.php';

if ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Kasir') {
  header('Location: index.php');
  exit();
}

$recordsPerPage = 2;
$currentPage = 1;
$myInput = '';

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int)$_GET['page'];
}

if (isset($_GET['myInput'])) {
    $myInput = $_GET['myInput'];
}

$offset = ($currentPage - 1) * $recordsPerPage;

$stmt = mysqli_prepare($conn, "SELECT * FROM member WHERE nama_member LIKE ? ORDER BY id_member DESC LIMIT ?, ?");
$searchParam = "%" . $myInput . "%";
mysqli_stmt_bind_param($stmt, "sii", $searchParam, $offset, $recordsPerPage);
mysqli_stmt_execute($stmt);

$get = mysqli_stmt_get_result($stmt);

$totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT id_member FROM member WHERE nama_member LIKE '%$myInput%'"));
$totalPages = ceil($totalRecords / $recordsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>APK Transaksi - Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style.css">
  </head>
  <body>
  <?php
    include 'nav.php';
    ?>
    <div class="container-fluid content">
      <div class="row">
        <div class="col-md-3">
        <form action="" method="get">
           <input type="search" class="form-control my-1" name="myInput" value="<?= $myInput ?>" autocomplete="off" placeholder="Cari">
        </div>
        <div class="col-md-9">
          <button type="submit" class="btn btn-success my-1">
            <i class="bi bi-search"></i>
          </button>
          <button type="button" class="btn btn-secondary my-1 float-end" data-bs-toggle="modal" data-bs-target="#PelangganAddModal">
            <i class="bi bi-plus-lg"></i>
                  </button>
        </div>
        </form>
        </div>
        <div class="row mt-3">
        <div class="col-12">
        <?php
while($pl=mysqli_fetch_assoc($get)){
    $id_member = $pl['id_member'];
    $nama_member = $pl['nama_member'];
    $alamat_member = $pl['alamat_member'];
    $telepon_member = $pl['telepon_member'];
    $email_member = $pl['email_member'];
        ?>
        <div class="cari">
          <h4 class="fw-bold"><?=$nama_member;?></h4>
          <p>
          Alamat : <?=$alamat_member;?><br />
          Telepon : <?=$telepon_member;?><br />
          Email Member : <?=$email_member;?><br />
          <div class="mt-1 text-end">
          <button type="button" value="<?= $pl['id_member'];?>" class="EditPelangganBtn btn btn-warning"><i class="bi bi-pencil-square"></i></button>
          <button type="button" value="<?= $pl['id_member'];?>" class="DeletePelangganBtn btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
          </div>
          </p>
          <hr />
          </div>
          <?php }; ?>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item<?= ($currentPage == 1) ? ' disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&myInput=<?= $myInput ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);

                        for ($i = $startPage; $i <= $endPage; $i++) {
                            echo "<li class='page-item" . ($currentPage == $i ? " active" : "") . "'><a class='page-link' href='?page=$i&myInput=$myInput'>$i</a></li>";
                        }
                        ?>

                        <!-- Next Page Link -->
                        <li class="page-item<?= ($currentPage == $totalPages) ? ' disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&myInput=<?= $myInput ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

          <!-- Modal Edit -->
 <div class="modal fade" id="PelangganEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Pelanggan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
          <form id="UpdatePelanggan">
          <div id="errorMessageUpdate" class="alert alert-danger d-none"></div>
          <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
              <input type="text" class="form-control" placeholder="Nama Pelanggan" name="nama_member" autocomplete="off" id="view_nama_member" required />
            <label for="alamat_pelanggan" class="form-label mt-1">Alamat Pelanggan</label>
              <input type="text" class="form-control" placeholder="Alamat Pelanggan" name="alamat_member" autocomplete="off" id="view_alamat_member" required />
            <label for="telepon_pelanggan" class="form-label mt-1">Telepon Pelanggan</label>
              <input type="number" class="form-control" placeholder="Telepon Pelanggan" name="telepon_member" autocomplete="off" id="view_telepon_member" required />
            <label for="email_pelanggan" class="form-label mt-1">Email Pelanggan</label>
              <input type="email" class="form-control" placeholder="Email Pelanggan" name="email_member" autocomplete="off" id="view_email_member" required />
              <input type="hidden" name="id_member" id="id_member" />
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
 <div class="modal fade" id="PelangganAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pelanggan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
          <form id="SavePelanggan">
          <!-- <form method="post"> -->
            <div id="errorMessage" class="alert alert-danger d-none"></div>
            <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
              <input type="text" class="form-control" placeholder="Nama Pelanggan" name="nama_member" autocomplete="off" required />
            <label for="alamat_pelanggan" class="form-label mt-1">Alamat Pelanggan</label>
              <input type="text" class="form-control" placeholder="Alamat Pelanggan" name="alamat_member" autocomplete="off" required />
            <label for="telepon_pelanggan" class="form-label mt-1">Telepon Pelanggan</label>
              <input type="number" class="form-control" placeholder="Telepon Pelanggan" name="telepon_member" autocomplete="off" required />
            <label for="email_pelanggan" class="form-label mt-1">Email Pelanggan</label>
              <input type="email" class="form-control" placeholder="Email Pelanggan" name="email_member" autocomplete="off" required />
              <input type="hidden" name="id_toko" id="id_toko" />
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
