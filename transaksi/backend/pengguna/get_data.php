<?php
require '../function.php';

if (!isset($_SESSION['log']) || $_SESSION['role'] !== 'Admin') {
    $response = [
        'status' => 403,
        'message' => 'Forbidden'
    ];
} else {
    if (isset($_GET['id_user'])) {
        $id_user = mysqli_real_escape_string($conn, $_GET['id_user']);
        $query = "SELECT * FROM login WHERE id_user = '$id_user'";
    } else {
        $query = "SELECT * FROM login";
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
