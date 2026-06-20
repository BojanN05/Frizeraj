<?php
session_start();      // Pokreće sesiju
session_unset();      // Briše sve promenljive iz sesije
session_destroy();    // Uništava sesiju
header("Location: ../public/index.php"); // Preusmerava na početnu
exit;
?>