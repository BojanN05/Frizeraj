<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /Frizeraj/public/login.php");
    exit;
}


