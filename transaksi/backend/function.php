<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "pkk_transaksi");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cekdatabase = mysqli_query($conn, "SELECT * FROM login WHERE username ='$username'");
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $row = mysqli_fetch_array($cekdatabase);
        if (password_verify($password, $row['password'])) {
            $_SESSION['log'] = 'True';
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['id_member'] = $row['id_member'];
            header('location:index.php');
        } else {
            echo '
            <script>alert("Username dan Password Tidak Sesuai");
            window.location.href="login.php"
            </script>';
        }
    }
}


// Insert Pengguna
if (isset($_POST['insert_pengguna'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $id_member = $_POST['id_member'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkDuplicateStmt = $conn->prepare("SELECT COUNT(*) FROM login WHERE username = ? OR id_member = ?");
    $checkDuplicateStmt->bind_param("si", $username, $id_member);
    $checkDuplicateStmt->execute();
    $checkDuplicateStmt->bind_result($count);
    $checkDuplicateStmt->fetch();
    $checkDuplicateStmt->close();

    if ($count > 0) {
        $response = array(
            'status' => 1,
            'message' => 'Username Atau Anggota Sudah Ada'
        );
    } else {
        $stmt = $conn->prepare("INSERT INTO login (username, password, role, id_member) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $hashedPassword, $role, $id_member);

        if ($stmt->execute()) {
            $response = array(
                'status' => 200,
                'message' => 'Pengguna Berhasil Ditambahkan'
            );
        } else {
            $response = array(
                'status' => 1,
                'message' => 'Gagal Ditambahkan'
            );
        }

        $stmt->close();
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Edit Pengguna
if (isset($_POST['edit_pengguna'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $id_member = $_POST['id_member'];
    $id_user = $_POST['id_user'];

    // Ambil password yang sudah di-hash dari database
    $getPasswordStmt = $conn->prepare("SELECT password FROM login WHERE id_user = ?");
    $getPasswordStmt->bind_param("i", $id_user);
    $getPasswordStmt->execute();
    $getPasswordStmt->bind_result($hashedPassword);
    $getPasswordStmt->fetch();
    $getPasswordStmt->close();

    if (empty($password)) {
        $hashedPassword = $hashedPassword;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    $checkDuplicateStmt = $conn->prepare("SELECT COUNT(*) FROM login WHERE (username = ? OR id_member = ?) AND id_user != ?");
    $checkDuplicateStmt->bind_param("sii", $username, $id_member, $id_user);
    $checkDuplicateStmt->execute();
    $checkDuplicateStmt->bind_result($count);
    $checkDuplicateStmt->fetch();
    $checkDuplicateStmt->close();

    if ($count > 0) {
        $response = array(
            'status' => 1,
            'message' => 'Data Sudah Ada Di Dalam Database'
        );
    } else {
        $stmt = $conn->prepare("UPDATE login SET username = ?, password = ?, role = ?, id_member = ? WHERE id_user = ?");
        $stmt->bind_param("sssii", $username, $hashedPassword, $role, $id_member, $id_user);

        if ($stmt->execute()) {
            $response = array(
                'status' => 200,
                'message' => 'Pengguna Berhasil Diupdate'
            );
        } else {
            $response = array(
                'status' => 1,
                'message' => 'Gagal Diupdate'
            );
        }
        $stmt->close();
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Hapus Pengguna
if(isset($_POST['delete_pengguna'])){
    $id_user = $_POST['id_user'];

    $query = mysqli_query($conn,"DELETE FROM login WHERE id_user='$id_user'");
    if($query){
        $response = array(
            'status' => 200,
            'message' => 'Pengguna Berhasil Di Hapus'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Di Hapus'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Insert Pelanggan
if (isset($_POST['insert_member'])) {
    $nama_member = $_POST['nama_member'];
    $alamat_member = $_POST['alamat_member'];
    $telepon_member = $_POST['telepon_member'];
    $email_member = $_POST['email_member'];

    $stmt = $conn->prepare("INSERT INTO member (nama_member, alamat_member, telepon_member, email_member) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_member, $alamat_member, $telepon_member, $email_member);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Pelanggan Berhasil Ditambahkan'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Ditambahkan'
        );
    }

    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Edit Pelanggan
if (isset($_POST['edit_member'])) {
    $nama_member = $_POST['nama_member'];
    $alamat_member = $_POST['alamat_member'];
    $telepon_member = $_POST['telepon_member'];
    $email_member = $_POST['email_member'];
    $id_member = $_POST['id_member'];

    $stmt = $conn->prepare("UPDATE member SET nama_member = ?, alamat_member = ?, telepon_member = ?, email_member = ? WHERE id_member = ?");
    $stmt->bind_param("ssssi", $nama_member, $alamat_member, $telepon_member, $email_member, $id_member);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Pelanggan Berhasil Diupdate'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Diupdate'
        );
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Hapus Pelanggan
if(isset($_POST['delete_member'])){
    $id_member = $_POST['id_member'];

    $query = mysqli_query($conn,"DELETE FROM member WHERE id_member='$id_member'");
    if($query){
        $response = array(
            'status' => 200,
            'message' => 'Pelanggan Berhasil Di Hapus'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Di Hapus'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Insert Toko
if (isset($_POST['insert_toko'])) {
    $nama_toko = $_POST['nama_toko'];
    $alamat_toko = $_POST['alamat_toko'];
    $telepon_toko = $_POST['telepon_toko'];
    $nama_pemilik = $_POST['nama_pemilik'];

    $stmt = $conn->prepare("INSERT INTO toko (nama_toko, alamat_toko, telepon_toko, nama_pemilik) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_toko, $alamat_toko, $telepon_toko, $nama_pemilik);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Toko Berhasil Ditambahkan'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Ditambahkan'
        );
    }

    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Edit Toko
if (isset($_POST['edit_toko'])) {
    $nama_toko = $_POST['nama_toko'];
    $alamat_toko = $_POST['alamat_toko'];
    $telepon_toko = $_POST['telepon_toko'];
    $nama_pemilik = $_POST['nama_pemilik'];
    $id_toko = $_POST['id_toko'];

    $stmt = $conn->prepare("UPDATE toko SET nama_toko = ?, alamat_toko = ?, telepon_toko = ?, nama_pemilik = ? WHERE id_toko = ?");
    $stmt->bind_param("ssssi", $nama_toko, $alamat_toko, $telepon_toko, $nama_pemilik, $id_toko);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Toko Berhasil Diupdate'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Diupdate'
        );
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Hapus Toko
if(isset($_POST['delete_toko'])){
    $id_toko = $_POST['id_toko'];

    $query = mysqli_query($conn,"DELETE FROM toko WHERE id_toko='$id_toko'");
    if($query){
        $response = array(
            'status' => 200,
            'message' => 'Toko Berhasil Di Hapus'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Di Hapus'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Insert Transaksi
if (isset($_POST['insert_transaksi'])) {
    $id_member = $_POST['id_member'];
    $id_toko = $_POST['id_toko'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $total_harga = $_POST['total_harga'];
    $tanggal_bayar = empty($_POST['tanggal_bayar']) ? null : $_POST['tanggal_bayar'];
    $keterangan = empty($_POST['keterangan']) ? null : $_POST['keterangan'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO transaksi (id_member, id_toko, tanggal_transaksi, total_harga, tanggal_bayar, keterangan, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdsss", $id_member, $id_toko, $tanggal_transaksi, $total_harga, $tanggal_bayar, $keterangan, $status);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Transaksi Berhasil Ditambahkan'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Ditambahkan'
        );
    }

    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
}


// Edit Transaksi
if (isset($_POST['edit_transaksi'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $id_member = $_POST['id_member'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $total_harga = $_POST['total_harga'];
    $tanggal_bayar = empty($_POST['tanggal_bayar']) ? null : $_POST['tanggal_bayar'];
    $keterangan = empty($_POST['keterangan']) ? null : $_POST['keterangan'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE transaksi SET id_member = ?, tanggal_transaksi = ?, total_harga = ?, tanggal_bayar = ?, keterangan = ?, status = ? WHERE id_transaksi = ?");
    $stmt->bind_param("isssssi", $id_member, $tanggal_transaksi, $total_harga, $tanggal_bayar, $keterangan, $status, $id_transaksi);

    if ($stmt->execute()) {
        $response = array(
            'status' => 200,
            'message' => 'Transaksi Berhasil Diupdate'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Diupdate'
        );
    }
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Delete Transaksi
if(isset($_POST['delete_transaksi'])){
    $id_transaksi = $_POST['id_transaksi'];

    $query = mysqli_query($conn,"DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'");
    if($query){
        $response = array(
            'status' => 200,
            'message' => 'Transaksi Berhasil Di Hapus'
        );
    } else {
        $response = array(
            'status' => 1,
            'message' => 'Gagal Di Hapus'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>