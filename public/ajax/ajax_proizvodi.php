<?php
require '../../app/confing/database.php'; 
$pretraga = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortiranje = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$stranica = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$proizvodaPoStranici = 3; 
$offset = ($stranica - 1) * $proizvodaPoStranici;
$sql = "FROM products WHERE 1=1";
if (!empty($pretraga)) {
    $sql .= " AND name LIKE ?";
}

$countSql = "SELECT COUNT(*) as ukupno " . $sql;
$stmtCount = $conn->prepare($countSql);
if (!empty($pretraga)) {
    $likePretraga = "%$pretraga%";
    $stmtCount->bind_param("s", $likePretraga);
}
$stmtCount->execute();
$ukupnoProizvoda = $stmtCount->get_result()->fetch_assoc()['ukupno'];
$ukupnoStranica = ceil($ukupnoProizvoda / $proizvodaPoStranici);
$stmtCount->close();


$glavniSql = "SELECT * " . $sql;
if ($sortiranje === 'price_asc') {
    $glavniSql .= " ORDER BY price ASC";
} elseif ($sortiranje === 'price_desc') {
    $glavniSql .= " ORDER BY price DESC";
} else {
    $glavniSql .= " ORDER BY id_product DESC"; 
}


$glavniSql .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($glavniSql);

if (!empty($pretraga)) {
    $stmt->bind_param("sii", $likePretraga, $proizvodaPoStranici, $offset);
} else {
    $stmt->bind_param("ii", $proizvodaPoStranici, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$proizvodi = [];
while ($row = $result->fetch_assoc()) {
    $proizvodi[] = $row;
}
$stmt->close();

header('Content-Type: application/json');
echo json_encode([
    'proizvodi' => $proizvodi,
    'trenutnaStranica' => $stranica,
    'ukupnoStranica' => $ukupnoStranica
]);