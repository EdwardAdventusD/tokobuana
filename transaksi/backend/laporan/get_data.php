<?php
require '../function.php';

$response = null;
$i = 1;

$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

$filterYear = null;
$filterMonth = null;

if ($filter !== null) {
    list($filterYear, $filterMonth) = explode('-', $filter);

    if (!is_numeric($filterYear) || !is_numeric($filterMonth) || $filterMonth < 1 || $filterMonth > 12) {
        $filterYear = null;
        $filterMonth = null;
    }
}

$whereCondition = '';

if ($filterMonth !== null) {
    $whereCondition .= " AND MONTH(tr.tanggal_transaksi) = $filterMonth";
}

if ($filterYear !== null) {
    $whereCondition .= " AND YEAR(tr.tanggal_transaksi) = $filterYear";
}

if ($filterMonth !== null || $filterYear !== null) {
    $query = "SELECT tr.*, mb.id_member, mb.nama_member
              FROM transaksi tr 
              JOIN member mb
              ON tr.id_member=mb.id_member
              WHERE 1 $whereCondition
              ORDER BY tr.tanggal_transaksi DESC";

    $get = mysqli_query($conn, $query);

    while ($tr = mysqli_fetch_array($get)) {
        $response[] = [
            'no' => $i++,
            'tanggal_transaksi' => $tr['tanggal_transaksi'],
            'nama_member' => $tr['nama_member'],
            'total_harga' => number_format($tr['total_harga']),
            'status' => $tr['status'],
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>
