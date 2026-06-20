<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../app/confing/database.php';

$menuItems = [];
$result = $conn->query("SELECT * FROM menu WHERE position='header' AND is_active=1 ORDER BY sort_order ASC");
if ($result) {
    while($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="../public/index.php">
            <img src="../assets/images/logo-removebg.png" alt="Frizerski Salon" height="50">Blade & Comb
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach($menuItems as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == basename($item['url']) ? 'active' : '' ?>" 
                           href="<?= $item['url'] ?>">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="d-flex gap-2">
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="../public/login.php" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="../public/register.php" class="btn btn-frizer btn-sm">Register</a>
                <?php else: ?>
                    <?php if (isset($_SESSION['user']['id_role']) && $_SESSION['user']['id_role'] == 1): ?>
    <a href="../admin/index.php" class="btn btn-warning btn-sm">Admin</a>
<?php endif; ?>
                    <a href="../public/logout.php" class="btn btn-frizer btn-sm">Logout</a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>
