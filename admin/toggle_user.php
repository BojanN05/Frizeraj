<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /Frizeraj/public/login.php");
    exit;
}

// samo admin (id_role = 1)
if ($_SESSION['user']['id_role'] != 1) {
    header("Location: /Frizeraj/public/index.php");
    exit;
}
?>

<?php
require 'admin.php';
require '../app/confing/database.php';

if (!isset($_GET['id'], $_GET['status'])) {
    header("Location: user.php");
    exit;
}

$id = (int) $_GET['id'];
$currentStatus = (int) $_GET['status'];
$newStatus = $currentStatus ? 0 : 1;

// Ne diramo admina
$stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id_user = ? AND id_role != 1");
$stmt->bind_param("ii", $newStatus, $id);
$stmt->execute();

header("Location: user.php");
exit;
