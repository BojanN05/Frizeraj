<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
    header("Location: /Frizeraj/public/index.php");
    exit;
}

require '../app/confing/database.php';

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM books WHERE id_book = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: bookings.php");
exit;