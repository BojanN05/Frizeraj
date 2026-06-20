<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/Helper/logger.php'; 
zabeleziPristup(); 
?>

<?php require '../app/layout/head.php'; ?>
<?php require '../app/layout/header.php'; ?>


<!-- HERO / SLIDER -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../assets/images/book.jpg" class="d-block w-100" alt="Slider 1">
            <div class="carousel-caption d-none d-md-block carousel-caption-top">
                <h1>Dobrodošli u naš frizerski salon</h1>
                <h3>Profesionalno šišanje i stilizovanje brade</h3>
                <a href="../public/book.php" class="btn btn-primary btn-frizer">Rezerviši termin</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="../assets/images/frizer.jpg" class="d-block w-100" alt="Slider 2">
        </div>
        <div class="carousel-item">
            <img src="../assets/images/beard.webp" class="d-block w-100" alt="Slider 3">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- TIM -->
<section class="container mt-5">
    <h2 class="text-center mb-4">Naš tim</h2>
    <div class="row g-4">
        <div class="col-md-4 text-center">
            <img src="../assets/images/frizer1.jpg" class="rounded-circle mb-2" width="200" alt="Stylist 1">
            <h5>Marko Petrović</h5>
            <p>Glavni frizer, stručnjak za muško šišanje</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="../assets/images/pex.jpg" class="rounded-circle mb-2" width="200" alt="Stylist 2">
            <h5>Jelena Jovanović</h5>
            <p>Stilizovanje i bojenje kose</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="../assets/images/frizer2.jpg" class="rounded-circle mb-2" width="200" alt="Stylist 3">
            <h5>Ivan Ilić</h5>
            <p>Brada i detaljno oblikovanje</p>
        </div>
    </div>
</section>

<!-- KONTAKT -->
<section class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Kontakt</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <ul class="list-unstyled text-center contact-info">
                <li><i class="fa-solid fa-phone me-2"></i> 061/123-4567</li>
                <li><i class="fa-solid fa-envelope me-2"></i> info@frizerskisalon.rs</li>
                <li><i class="fa-solid fa-clock me-2"></i> Pon-Pet 09:00-19:00</li>
            </ul>
        </div>
    </div>
     <div class="col-12 d-flex justify-content-end">
    <a href="https://wa.me/0611234567" class="btn btn-frizer" target="_blank">
    <i class="fa-brands fa-whatsapp me-1"></i> Rezerviši preko WhatsApp
</a>
</div>

<div class="d-flex align-items-center gap-3 mt-4 mb-4">
    <i class="fa-solid fa-user fa-2x"></i> 
    <div>
        <span id="clientCounter" class="fs-3 fw-bold">0</span>
        <span> zadovoljnih klijentkinja i klijenata – pridruži im se</span>
    </div>
</div>
<div class="mt-4 mb-4">
    <iframe
        src="https://www.google.com/maps?q=Zdravka+Celara+16,Beograd,Serbia&output=embed"
        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
</div>
</section>


<!-- PROIZVODI -->
<section class="container mt-5">
    <h2 class="text-center mb-4">Naši proizvodi</h2>
    <div class="row g-4">

        <!-- Proizvod 1 -->
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <img src="../assets/images/N9.png" class="card-img-top" alt="Šampon">
                <div class="card-body text-center">
                    <h5 class="card-title">Šampon za muškarce</h5>
                    <p class="card-text">Održava kosu zdravom i sjajnom.</p>
                    <p class="fw-bold">Cena: 1200 RSD</p>
                    <button type="button" class="btn btn-frizer" data-bs-toggle="modal" data-bs-target="#product1Modal">
                        Više info
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal za Proizvod 1 -->
        <div class="modal fade" id="product1Modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Šampon za muškarce</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="../assets/images/N9.png" class="img-fluid mb-3" alt="Šampon">
                        <p>Profesionalni šampon za muškarce koji održava kosu zdravom i sjajnom. Pogodan za sve tipove kose.</p>
                        <p class="fw-bold">Cena: 1200 RSD</p>
                    </div>
                    <div class="modal-footer">
                        <a href="../public/proizvodi.php" class="btn btn-frizer">Pogledaj proizvod</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proizvod 2 -->
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <img src="../assets/images/beardOil.png" class="card-img-top" alt="Ulje za bradu">
                <div class="card-body text-center">
                    <h5 class="card-title">Ulje za bradu</h5>
                    <p class="card-text">Nega i stilizovanje brade svaki dan.</p>
                    <p class="fw-bold">Cena: 1200 RSD</p>
                    <button type="button" class="btn btn-frizer" data-bs-toggle="modal" data-bs-target="#product2Modal">
                        Više info
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal za Proizvod 2 -->
        <div class="modal fade" id="product2Modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ulje za bradu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="../assets/images/beardOil.png" class="img-fluid mb-3" alt="Ulje za bradu">
                        <p>Nega i stilizovanje brade svaki dan. Dodaje sjaj i olakšava oblikovanje brade.</p>
                        <p class="fw-bold">Cena: 1200 RSD</p>
                    </div>
                    <div class="modal-footer">
                        <a href="../public/proizvodi.php" class="btn btn-frizer">Pogledaj proizvod</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proizvod 3 -->
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <img src="../assets/images/N15.jpg" class="card-img-top" alt="Gel za kosu">
                <div class="card-body text-center">
                    <h5 class="card-title">Gel za kosu</h5>
                    <p class="card-text">Savršena frizura ceo dan bez masnog efekta.</p>
                    <p class="fw-bold">Cena: 900 RSD</p>
                    <button type="button" class="btn btn-frizer" data-bs-toggle="modal" data-bs-target="#product3Modal">
                        Više info
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal za Proizvod 3 -->
        <div class="modal fade" id="product3Modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Gel za kosu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="../assets/images/N15.jpg" class="img-fluid mb-3" alt="Gel za kosu">
                        <p>Gel za kosu koji drži frizuru ceo dan, bez masnog izgleda. Pogodan za sve tipove kose.</p>
                        <p class="fw-bold">Cena: 900 RSD</p>
                    </div>
                    <div class="modal-footer">
                        <a href="../public/proizvodi.php" class="btn btn-frizer">Pogledaj proizvod</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proizvod 4 -->
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <img src="../assets/images/cetka1.webp" class="card-img-top" alt="Četka">
                <div class="card-body text-center">
                    <h5 class="card-title">Četka za bradu</h5>
                    <p class="card-text">Četka za bradu za svakog muškarca.</p>
                    <p class="fw-bold">Cena: 500 RSD</p>
                    <button type="button" class="btn btn-frizer" data-bs-toggle="modal" data-bs-target="#product4Modal">
                        Više info
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal za Proizvod 4 -->
        <div class="modal fade" id="product4Modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Četka za bradu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="../assets/images/cetka1.webp" class="img-fluid mb-3" alt="Četka">
                        <p>Četka za bradu koja olakšava oblikovanje i održavanje brade.</p>
                        <p class="fw-bold">Cena: 500 RSD</p>
                    </div>
                    <div class="modal-footer">
                        <a href="../public/proizvodi.php" class="btn btn-frizer">Pogledaj proizvod</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>