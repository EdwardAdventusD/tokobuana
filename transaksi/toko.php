<?php
require 'backend/function.php';
require 'backend/auth.php';

if ($_SESSION['role'] != 'Admin') {
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

$stmt = mysqli_prepare($conn, "SELECT * FROM toko WHERE nama_toko LIKE ? ORDER BY id_toko DESC LIMIT ?, ?");
$searchParam = "%" . $myInput . "%";
mysqli_stmt_bind_param($stmt, "sii", $searchParam, $offset, $recordsPerPage);
mysqli_stmt_execute($stmt);

$get = mysqli_stmt_get_result($stmt);

$totalRecords = mysqli_num_rows(mysqli_query($conn, "SELECT id_toko FROM toko WHERE nama_toko LIKE '%$myInput%'"));
$totalPages = ceil($totalRecords / $recordsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>APK Transaksi - Toko</title>
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
          <button type="button" class="btn btn-secondary my-1 float-end" data-bs-toggle="modal" data-bs-target="#TokoAddModal">
            <i class="bi bi-plus-lg"></i>
                  </button>
        </div>
        </form>
        </div>
        <div class="row mt-3">
        <div class="col-12">
        <?php
while($tk=mysqli_fetch_assoc($get)){
    $id_toko = $tk['id_toko'];
    $nama_toko = $tk['nama_toko'];
    $alamat_toko = $tk['alamat_toko'];
    $telepon_toko = $tk['telepon_toko'];
    $nama_pemilik = $tk['nama_pemilik'];
        ?>
        <div class="cari">
          <h4 class="fw-bold"><?=$nama_toko;?></h4>
          <p>
          Alamat : <?=$alamat_toko;?><br />
          Telepon : <?=$telepon_toko;?><br />
          Nama Pemilik : <?=$nama_pemilik;?><br />
          <div class="mt-1 text-end">
          <button type="button" value="<?= $tk['id_toko'];?>" class="EditTokoBtn btn btn-warning"><i class="bi bi-pencil-square"></i></button>
          <button type="button" value="<?= $tk['id_toko'];?>" class="DeleteTokoBtn btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
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
 <div class="modal fade" id="TokoEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Toko</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
          <form id="UpdateToko">
          <div id="errorMessageUpdate" class="alert alert-danger d-none"></div>
          <label for="nama_toko" class="form-label">Nama Toko</label>
              <input type="text" class="form-control" placeholder="Nama Toko" name="nama_toko" autocomplete="off" id="view_nama_toko" required />
            <label for="alamat_toko" class="form-label mt-1">Alamat Toko</label>
              <input type="text" class="form-control" placeholder="Alamat Toko" name="alamat_toko" autocomplete="off" id="view_alamat_toko" required />
            <label for="telepon_toko" class="form-label mt-1">Telepon Toko</label>
              <input type="number" class="form-control" placeholder="Telepon Toko" name="telepon_toko" autocomplete="off" id="view_telepon_toko" required />
            <label for="nama_pemilik" class="form-label mt-1">Nama Pemilik</label>
              <input type="text" class="form-control" placeholder="Nama Pemilik" name="nama_pemilik" autocomplete="off" id="view_nama_pemilik" required />
              <input type="hidden" name="id_toko" id="id_toko" />
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

 <!-- Modal Insert -->
 <div class="modal fade" id="TokoAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Toko</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
            <div class="modal-body">
          <form id="SaveToko">
          <!-- <form method="post"> -->
            <div id="errorMessage" class="alert alert-danger d-none"></div>
            <label for="nama_toko" class="form-label">Nama Toko</label>
              <input type="text" class="form-control" placeholder="Nama Toko" name="nama_toko" autocomplete="off" required />
            <label for="alamat_toko" class="form-label mt-1">Alamat Toko</label>
              <input type="text" class="form-control" placeholder="Alamat Toko" name="alamat_toko" autocomplete="off" required />
            <label for="telepon_toko" class="form-label mt-1">Telepon Toko</label>
              <input type="number" class="form-control" placeholder="Telepon Toko" name="telepon_toko" autocomplete="off" required />
            <label for="nama_pemilik" class="form-label mt-1">Nama Pemilik</label>
              <input type="text" class="form-control" placeholder="Nama Pemilik" name="nama_pemilik" autocomplete="off" required />
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
