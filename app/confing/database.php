<?php
$host = "localhost";      
$db_name = "frizerski_salon"; 
$username = "root";       
$password = "";           

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Greška prilikom povezivanja na bazu: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
