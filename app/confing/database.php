<?php
$envPath = dirname(__DIR__, 2) . "/.env";
if (file_exists($envPath)) {
    $files = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $hostDefined = false;
    foreach ($files as $file) {
        if (strpos(trim($file), "#") === 0) continue;
        
        list($key, $value) = explode('=', $file, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'"); 
        if ($key === "DB_HOST" && !defined("SERVER")) { define("SERVER", $value); $hostDefined = true; }
        if ($key === "DB_DATABASE" && !defined("DATABASE")) define("DATABASE", $value); 
        if ($key === "DB_USERNAME" && !defined("USERNAME")) define("USERNAME", $value); 
        if ($key === "DB_PASSWORD" && !defined("PASSWORD")) define("PASSWORD", $value); 
    }
    
    if (!$hostDefined && !defined("SERVER")) {
        define("SERVER", "localhost");
        define("DATABASE", "frizerski_salon");
        define("USERNAME", "root");
        define("PASSWORD", "");
    }
} else {
    if (!defined("SERVER")) define("SERVER", "localhost");
    if (!defined("DATABASE")) define("DATABASE", "frizerski_salon");
    if (!defined("USERNAME")) define("USERNAME", "root");
    if (!defined("PASSWORD")) define("PASSWORD", "");
}
$conn = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Greška prilikom povezivanja na bazu: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>