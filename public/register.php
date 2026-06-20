<?php
session_start();
require '../app/confing/database.php';
require '../app/layout/head.php';
require '../app/layout/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $role_id = 2; 
    $is_active = 0; 
    $token = bin2hex(random_bytes(32));

    
    $nameParts = explode(" ", $fullName, 2);
    $first_name = $nameParts[0] ?? '';
    $last_name = $nameParts[1] ?? '';

    // VALIDACIJA
    if (empty($fullName)) $errors[] = "Ime i prezime je obavezno.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email nije validan.";
    if (strlen($password) < 6) $errors[] = "Lozinka mora imati najmanje 6 karaktera.";
    if ($password !== $confirmPassword) $errors[] = "Lozinke se ne poklapaju.";

    // PROVERA EMAILA
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Korisnik sa ovim emailom već postoji.";
    }
    $stmt->close();

    
    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (first_name, last_name, email, password, id_role, is_active, activation_token, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "ssssiss",
            $first_name,
            $last_name,
            $email,
            $hashedPassword,
            $role_id,
            $is_active,
            $token
        );

        if ($stmt->execute()) {

            // LINK ZA AKTIVACIJU
            $activationLink = "http://localhost/Frizeraj/auth/activate.php?token=$token";

            
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'bojannovakovic49@gmail.com'; 
                $mail->Password   = 'lzaf eyec znrm lwfy';  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('bojannovakovic49@gmail.com', 'Frizeraj');
                $mail->addAddress($email, $first_name);

                $mail->isHTML(true);
                $mail->Subject = 'Aktivacija naloga - Frizeraj';
                $mail->Body    = "
                    Zdravo <b>$first_name</b>,<br><br>
                    Kliknite na link ispod kako biste aktivirali svoj nalog:<br><br>
                    <a href='$activationLink'>$activationLink</a><br><br>
                    Ako se niste vi registrovali, ignorišite ovaj email.
                ";

                $mail->send();

                $success = "Registracija uspešna! Proverite email i aktivirajte nalog.";
                $fullName = $email = "";

            } catch (Exception $e) {
                $errors[] = "Email nije poslat. Pokušajte kasnije.";
            }

        } else {
            $errors[] = "Došlo je do greške. Pokušajte ponovo.";
        }

        $stmt->close();
    }
}
?>

<section class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Registracija</h3>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Ime i prezime</label>
                        <input type="text" name="fullName" class="form-control"
                               value="<?= htmlspecialchars($fullName ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lozinka</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Potvrdi lozinku</label>
                        <input type="password" name="confirmPassword" class="form-control" required>
                    </div>

                    <button class="btn btn-frizer w-100">Registruj se</button>
                </form>

                <p class="text-center mt-3">
                    Već imate nalog? <a href="login.php">Prijavite se</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>
