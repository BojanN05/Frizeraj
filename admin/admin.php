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
