<?php
require '../app/helper/protect.php';
require '../app/confing/database.php';

$id_poll   = $_POST['id_polls'];
$id_answer = $_POST['answer'];

// Uzmima ID ulogovanog korisnika 
$id_user = $_SESSION['user']['id_user'] ?? null;

if (!$id_user) {
    $msg = "Morate biti ulogovani da glasate!";
    header("Location: book.php?msg=" . urlencode($msg));
    exit;
}

// Proveramo da li je korisnik već glasao za ovu anketu
$stmt = $conn->prepare("SELECT COUNT(*) FROM poll_votes WHERE id_polls = ? AND id_user = ?");
$stmt->bind_param("ii", $id_poll, $id_user);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if($count > 0){
    $msg = "Već ste glasali za ovu anketu!";
} else {
    // Ubacivanje glasa
    $stmt = $conn->prepare("INSERT INTO poll_votes (id_polls, id_answers, id_user) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id_poll, $id_answer, $id_user);
    if($stmt->execute()){
        $msg = "Hvala što ste glasali!";
    } else {
        $msg = "Došlo je do greške, probajte ponovo.";
    }
    $stmt->close();
}


header("Location: book.php?msg=" . urlencode($msg));
exit;
?>