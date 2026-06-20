<?php require '../app/layout/head.php'; ?>
<?php require '../app/layout/header.php'; ?>

<main class="min-vh-100 d-flex flex-column">

    <h2 class="text-center mt-4">Galerija</h2>

    <p class="text-center text-muted fs-5 mt-3 mb-4 px-3">
    Pogledajte deo naših realizovanih projekata i uverite se u kvalitet naše izrade.
    Klikom na jednu od kategorija ispod, prikazaće se fotografije koje najbolje predstavljaju naš rad.
</p>

    <div class="text-center mb-3">
        <button class="btn btn-dark gallery-btn" data-category="sisanje">Šišanje</button>
        <button class="btn btn-dark gallery-btn" data-category="frizura">Frizure</button>
        <button class="btn btn-dark gallery-btn" data-category="brada">Brade</button>
    </div>

   
    <div id="gallery" class="d-flex flex-wrap justify-content-center gap-3 flex-grow-1"></div>

</main>

<?php require '../app/layout/footer.php'; ?>
<?php require '../app/layout/scripts.php'; ?>