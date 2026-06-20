<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Admin Panel</a>

        <div class="d-flex gap-2">
            <span class="text-light">
                <?= $_SESSION['user']['first_name'] ?>
            </span>
            <a href="../public/index.php" class="btn btn-outline-light btn-sm">
                Nazad na sajt
            </a>
            <a href="../public/logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>
