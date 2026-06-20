<?php
require '../../app/confing/database.php';

$conn->set_charset("utf8mb4");

if (!isset($_GET['category']) || empty($_GET['category'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Category missing']);
    exit;
}

$category = $_GET['category'];
$stmt = $conn->prepare("
    SELECT image_path
    FROM galery
    WHERE category = ? AND is_active = 1
");
$stmt->bind_param("s", $category);
$stmt->execute();


$result = $stmt->get_result();

$images = [];

while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_path']; 
}

if (empty($images)) {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
echo json_encode($images);
exit;