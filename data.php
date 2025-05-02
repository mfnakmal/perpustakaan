<?php
// Load konfigurasi database dari inlislite3
$config = require 'C:\xampp\htdocs\inlislite3\common\config\main-local.php';

try {
    $pdo = new PDO(
        $config['components']['db']['dsn'],
        $config['components']['db']['username'],
        $config['components']['db']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Koneksi gagal: " . $e->getMessage()]);
    exit();
}

// Ambil parameter dari DataTables
$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$search = isset($_GET['search']['value']) ? trim($_GET['search']['value']) : '';
$draw = isset($_GET['draw']) ? (int)$_GET['draw'] : 1;

// Kolom yang tersedia untuk sorting
$columns = ["CATALOG_ID", "Judul"];
$columnIndex = isset($_GET['order'][0]['column']) ? (int)$_GET['order'][0]['column'] : 1;
$orderColumn = isset($columns[$columnIndex]) ? $columns[$columnIndex] : "CATALOG_ID";
$orderDir = (isset($_GET['order'][0]['dir']) && in_array($_GET['order'][0]['dir'], ["asc", "desc"])) ? $_GET['order'][0]['dir'] : "asc";

// Query dasar
$sqlBase = "FROM collections
            RIGHT JOIN catalogs ON collections.Catalog_id = catalogs.ID
            WHERE collections.ID IS NULL
            ";

// Query untuk total data sebelum filter
$stmtTotal = $pdo->query("SELECT COUNT(*) $sqlBase");
$totalData = $stmtTotal->fetchColumn();

// Jika ada pencarian, tambahkan filter
$sqlFilter = "";
$params = [];
if (!empty($search)) {
    $sqlFilter = " AND (catalogs.ID LIKE :search OR catalogs.Title LIKE :search)";
    $params[':search'] = "%$search%";
}

// Query untuk total data setelah filter
$stmtFiltered = $pdo->prepare("SELECT COUNT(*) $sqlBase $sqlFilter");
$stmtFiltered->execute($params);
$totalFiltered = $stmtFiltered->fetchColumn();

// Query utama dengan LIMIT & ORDER BY
$sqlFinal = "SELECT 
                    catalogs.ID AS CATALOG_ID,
                    catalogs.Title AS Judul
             $sqlBase
             $sqlFilter
             ORDER BY $orderColumn $orderDir
             LIMIT :start, :limit";

$stmt = $pdo->prepare($sqlFinal);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format data untuk DataTables
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

echo json_encode($response, JSON_PRETTY_PRINT);
