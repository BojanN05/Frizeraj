<?php
session_start();
require '../app/confing/database.php'; 
require '../app/layout/head.php';
require '../app/layout/header.php';
require_once __DIR__ . '/../app/helper/logger.php';
zabeleziPristup();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email nije validan.";
    }

    if (empty($password)) {
        $errors[] = "Lozinka je obavezna.";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("
            SELECT id_user, first_name, last_name, email, password, id_role, is_active, login_attempts, attempt_time, is_locked
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if ($user['is_locked'] == 1) {
                $vreme_locka = strtotime($user['attempt_time']);
                $trenutno_vreme = time();
                if (($trenutno_vreme - $vreme_locka) < 300) {
                    $errors[] = "Nalog je privremeno zaključan na 5 minuta zbog 3 neuspešna pokušaja.";
                } else {
                    $reset_stmt = $conn->prepare("UPDATE users SET login_attempts = 0, is_locked = 0, attempt_time = NULL WHERE id_user = ?");
                    $reset_stmt->bind_param("i", $user['id_user']);
                    $reset_stmt->execute();
                    $reset_stmt->close();
                    $user['is_locked'] = 0;
                    $user['login_attempts'] = 0;
                }
            }
            if (empty($errors)) {
                if ($user['is_active'] == 0) {
                    $errors[] = "Vaš nalog nije aktiviran. Proverite email.";
                } 
                elseif (!password_verify($password, $user['password'])) {
                    
                    $novi_pokusaji = $user['login_attempts'] + 1;
                    $trenutno_vreme_baza = date('Y-m-d H:i:s');

                    if ($novi_pokusaji >= 3) {
                        $update_stmt = $conn->prepare("UPDATE users SET login_attempts = ?, attempt_time = ?, is_locked = 1 WHERE id_user = ?");
                        $update_stmt->bind_param("ssi", $novi_pokusaji, $trenutno_vreme_baza, $user['id_user']);
                        $update_stmt->execute();
                        $update_stmt->close();
                        $to = $user['email'];
                        $subject = "Upozorenje: Nalog je zakljucan";
                        $message = "Postovani, zabelezena su 3 neuspesna pokusaja logovanja na Vas nalog u roku od 5 minuta. Nalog je zakljucan iz bezbednosnih razloga na narednih 5 minuta.";
                        $headers = "From: sigurnost@frizeraj.com\r\n" .
                                   "Reply-To: sigurnost@frizeraj.com\r\n" .
                                   "Content-Type: text/plain; charset=UTF-8\r\n" .
                                   "X-Mailer: PHP/" . phpversion();
                        @mail($to, $subject, $message, $headers);

                        $errors[] = "Pogrešna lozinka. Pokušali ste 3 puta i nalog je uspešno zaključan. Poslat Vam je sigurnosni email.";
                    } else {
                        $update_stmt = $conn->prepare("UPDATE users SET login_attempts = ?, attempt_time = ? WHERE id_user = ?");
                        $update_stmt->bind_param("ssi", $novi_pokusaji, $trenutno_vreme_baza, $user['id_user']);
                        $update_stmt->execute();
                        $update_stmt->close();

                        $preostalo = 3 - $novi_pokusaji;
                        $errors[] = "Pogrešna lozinka. Preostalo pokušaja: " . $preostalo;
                    }
                } 
                else {
                    $clear_stmt = $conn->prepare("UPDATE users SET login_attempts = 0, attempt_time = NULL, is_locked = 0 WHERE id_user = ?");
                    $clear_stmt->bind_param("i", $user['id_user']);
                    $clear_stmt->execute();
                    $clear_stmt->close();

                    $_SESSION['user'] = [
                        'id_user'    => $user['id_user'],
                        'first_name' => $user['first_name'],
                        'last_name'  => $user['last_name'],
                        'email'      => $user['email'],
                        'id_role'    => $user['id_role']
                    ];

                    header("Location: ../public/index.php");
                    exit;
                }
            }

        } else {
            $errors[] = "Korisnik sa ovim emailom ne postoji.";
        }

        $stmt->close();
    }
}
?>

<section class="text-center my-5">
    <h1>Dobrodošli nazad!</h1>
    <p class="text-muted">
        Prijavite se da rezervišete svoj termin i uživate u vrhunskim frizerskim uslugama
    </p>
</section>

<section class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Prijava</h3>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($email ?? '') ?>">
                        <div class="invalid-feedback">Unesite validnu email adresu</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lozinka</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                        <div class="invalid-feedback">Unesite lozinku</div>
                    </div>

                    <button class="btn btn-frizer w-100">Prijavi se</button>
                </form>

                <p class="text-center mt-3">
                    Nemate nalog? <a href="register.php">Registrujte se</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>