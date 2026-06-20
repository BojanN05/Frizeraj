<?php 
require '../app/helper/protect.php'; 
require '../app/confing/database.php';
require '../app/layout/head.php'; 
require '../app/layout/header.php'; 

$success = "";
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
    $fullName = trim($_POST['fullName']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $service  = $_POST['service'];
    $date     = $_POST['date'];
    $time     = $_POST['time'];

    // VALIDACIJA
    if (empty($fullName)) $errors[] = "Ime i prezime je obavezno.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email nije validan.";
    if (empty($phone)) $errors[] = "Telefon je obavezan.";
    if (!in_array($service, ['sisanje','brada','paket'])) $errors[] = "Izaberite validnu uslugu.";
    if (empty($date)) $errors[] = "Datum je obavezan.";
    if (empty($time)) $errors[] = "Vreme je obavezno.";

     if (empty($errors)) {
        $today = date("Y-m-d");

        if ($date < $today) {
            $errors[] = "Ne možete zakazati termin u prošlosti.";
        }

        if ($date === $today) {
            $currentTime = date("H:i");

            if  ($time <= $currentTime) {
                $errors[] = "Ne možete zakazati termin za vreme koje je već prošlo.";
            }
        }
    }

    // PROVERA PUNIH SATI
    if (empty($errors)) {
        $allowedHours = range(9, 19); 
        list($hour, $minute) = explode(":", $time);
        if (!in_array((int)$hour, $allowedHours) || (int)$minute !== 0) {
            $errors[] = "Vreme mora biti na pun sat između 09:00 i 19:00.";
        }
    }

    // PROVERA DA LI JE TERMIN ZAUZET
    if (empty($errors)) {
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM books WHERE date = ? AND time = ?");
        $stmtCheck->bind_param("ss", $date, $time);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            $errors[] = "Termin na datum $date u $time je već zauzet. Molimo izaberite drugi termin.";
        }
    }

    // UBACIVANJE U BAZU
    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO books (fullName, email, phone, service, date, time)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss", $fullName, $email, $phone, $service, $date, $time);

        if ($stmt->execute()) {
            $success = "Termin uspešno zakazan!";
            $fullName = $email = $phone = $service = $date = $time = "";
        } else {
            $errors[] = "Došlo je do greške prilikom zakazivanja termina. Pokušajte ponovo.";
        }
        $stmt->close();
    }
}
?>

<section class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Zakažite termin</h2>

            <?php if(!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- FORMA ZA TERMIN -->
            <form action="book.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="fullName" class="form-label">Ime i prezime</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required
                           value="<?= htmlspecialchars($fullName ?? '') ?>">
                    <div class="invalid-feedback">Unesite ime i prezime</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email adresa</label>
                    <input type="email" class="form-control" id="email" name="email" required
                           value="<?= htmlspecialchars($email ?? '') ?>">
                    <div class="invalid-feedback">Unesite validnu email adresu</div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="text" class="form-control" id="phone" name="phone" required
                           value="<?= htmlspecialchars($phone ?? '') ?>">
                    <div class="invalid-feedback">Unesite broj telefona</div>
                </div>

                <div class="mb-3">
                    <label for="service" class="form-label">Usluga</label>
                    <select class="form-select" id="service" name="service" required>
                        <option value="">Izaberite uslugu</option>
                        <option value="sisanje" <?= (isset($service) && $service=='sisanje') ? 'selected' : '' ?>>Muško šišanje</option>
                        <option value="brada" <?= (isset($service) && $service=='brada') ? 'selected' : '' ?>>Stilizovanje brade</option>
                        <option value="paket" <?= (isset($service) && $service=='paket') ? 'selected' : '' ?>>Šišanje + brada</option>
                    </select>
                    <div class="invalid-feedback">Izaberite uslugu</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Datum</label>
                        <input type="date" class="form-control" id="date" name="date" required
                        min="<?= date('Y-m-d') ?>"
                               value="<?= htmlspecialchars($date ?? '') ?>">
                        <div class="invalid-feedback">Unesite datum</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="time" class="form-label">Vreme</label>
                        <select class="form-select" id="time" name="time" required>
                            <option value="">Izaberite vreme</option>
                            <?php
                            for ($h = 9; $h <= 19; $h++) {
                                $hourStr = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
                                $selected = (isset($time) && $time == $hourStr) ? 'selected' : '';
                                echo "<option value='$hourStr' $selected>$hourStr</option>";
                            }
                            ?>
                        </select>
                        <div class="invalid-feedback">Unesite vreme</div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-frizer w-100">Zakaži</button>
            </form>
        </div>
    </div>
</section>


<?php

$poll = $conn->query("SELECT * FROM polls WHERE is_active = 1 ORDER BY id_polls DESC LIMIT 1")->fetch_assoc();

if($poll):
   
    $answers = $conn->query("SELECT * FROM polls_answers WHERE id_polls = {$poll['id_polls']}");
?>
<section class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h4><?= htmlspecialchars($poll['question']) ?></h4>

            <form action="vote.php" method="POST">
                <?php while($ans = $answers->fetch_assoc()): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answer" value="<?= $ans['id_answers'] ?>" required>
                        <label class="form-check-label"><?= htmlspecialchars($ans['answer_text']) ?></label>
                    </div>
                <?php endwhile; ?>
                <input type="hidden" name="id_polls" value="<?= $poll['id_polls'] ?>">
                <button type="submit" class="btn btn-primary mt-2">Glasaj</button>
            </form>

            <?php
           
            $answers = $conn->query("SELECT * FROM polls_answers WHERE id_polls = {$poll['id_polls']}");
            $total_votes = $conn->query("SELECT COUNT(*) FROM poll_votes WHERE id_polls = {$poll['id_polls']}")->fetch_row()[0];

            echo "<h5 class='mt-3'>Rezultati ankete:</h5>";
            while($ans = $answers->fetch_assoc()) {
                $votes = $conn->query("SELECT COUNT(*) FROM poll_votes WHERE id_answers = {$ans['id_answers']}")->fetch_row()[0];
                $percent = ($total_votes > 0) ? round($votes / $total_votes * 100) : 0;
                echo "<p>{$ans['answer_text']}: $votes glasova ($percent%)</p>";
            }
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>