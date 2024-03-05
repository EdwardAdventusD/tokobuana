<?php
require '../function.php';
    if (isset($_GET['id_transaksi'])) {
        $id_transaksi = mysqli_real_escape_string($conn, $_GET['id_transaksi']);
        $query = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
    } else {
        $query = "SELECT * FROM transaksi";
    }

    $result = mysqli_query($conn, $query);
    if ($result) {
        $data   = mysqli_fetch_assoc($result);
        $response = [
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        ];
    } else {
        $response = [
            'status' => 1,
            'error' => 'Error executing query: ' . mysqli_error($conn)
        ];
    }

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>
