<?php
require '../function.php';

if (!isset($_SESSION['log']) || $_SESSION['role'] !== 'Admin') {
    $response = [
        'status' => 403,
        'message' => 'Forbidden'
    ];
} else {
    if (isset($_GET['id_toko'])) {
        $id_toko = mysqli_real_escape_string($conn, $_GET['id_toko']);
        $query = "SELECT * FROM toko WHERE id_toko = '$id_toko'";
    } else {
        $query = "SELECT * FROM toko";
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
}

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>
