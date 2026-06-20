<?php require '../app/helper/protect.php'; ?>
<?php require '../app/layout/head.php'; ?>
<?php require '../app/layout/header.php'; ?>

<?php
$services = [];
$result = $conn->query("SELECT * FROM services WHERE is_active = 1");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>


    <section class="container my-5 text-center">
        <h1 class="mb-3">Naše usluge</h1>
            <p class="text-muted">
                Profesionalne frizerske i barber usluge prilagođene svakom stilu.
            </p>
    </section>

   <section class="container mb-5">
    <div class="row g-4">

        <?php foreach ($services as $service): ?>
            <div class="col-md-4">
                <div class="card h-100 text-center shadow-sm service-card">
                    <div class="card-body">
                        <i class="fa-solid fa-scissors fa-2x mb-3 text-warning"></i>

                        <h5 class="card-title">
                            <?= htmlspecialchars($service['tittle']) ?>
                        </h5>

                        <p class="card-text">
                            <?= htmlspecialchars($service['description']) ?>
                        </p>

                        <p class="fw-bold fs-5">
                            <?= (int)$service['price'] ?> RSD
                        </p>

                        <a href="../public/book.php?service=<?= $service['id_services'] ?>" 
                           class="btn btn-frizer">
                            Rezerviši
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>

<section class="container my-5 text-center">
    <h2 class="mb-4">Premium usluge</h2>

    <p class="fs-5">
    <i class="fa-solid fa-gem text-warning me-2"></i> VIP tretman (šišanje + brada + masaža glave) – <strong>2.500 RSD</strong>
</p>
    <p class="fs-5">
        <i class="fa-solid fa-gem text-warning me-2"></i> Nega kose i brade profesionalnim proizvodima
    </p>
</section>

<section class="text-center my-5">
    <h3>Spreman za novi stil?</h3>
    <a href="../public/book.php" class="btn btn-frizer btn-lg mt-3">
        Rezerviši termin
    </a>
</section>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>