<?php
function zabeleziPristup() {
    $logFajl = __DIR__ . '/../../data/pristupi.txt'; 
    
    $vreme = date('Y-m-d H:i:s');
    $stranica = $_SERVER['REQUEST_URI']; 
    $ipAdresa = $_SERVER['REMOTE_ADDR'];
    $korisnikID = isset($_SESSION['user']) ? ($_SESSION['user']['id'] ?? $_SESSION['user']['email'] ?? 'Ulogovan') : 'Gost';

    $zapis = "{$vreme}||{$stranica}||{$korisnikID}||{$ipAdresa}\n";

    file_put_contents($logFajl, $zapis, FILE_APPEND | LOCK_EX);
}