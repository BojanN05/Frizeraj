<?php
session_start();
require '../app/confing/database.php';
require '../app/layout/head.php';
require '../app/layout/header.php';

$token = $_GET['token'] ?? '';
$errors = [];
$success = "";

if ($token) {
    $stmt = $conn->prepare("SELECT id_user, is_active FROM users WHERE activation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['is_active'] == 1) {
            $errors[] = "Nalog je već aktiviran.";
        } else {
            // Aktiviraj nalog
            $stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE id_user = ?");
            $stmt->bind_param("i", $user['id_user']);
            if ($stmt->execute()) {
                $success = "Nalog je uspešno aktiviran! Možete se prijaviti.";
            } else {
                $errors[] = "Došlo je do greške. Pokušajte ponovo.";
            }
            $stmt->close();
        }
    } else {
        $errors[] = "Nevažeći token.";
    }
} else {
    $errors[] = "Token nije prosleđen.";
}
?>

<section class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Aktivacija naloga</h3>

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
                    <p class="text-center mt-3">
                        <a href="../public/login.php">Prijavite se</a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>
