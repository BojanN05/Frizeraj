<?php
require 'admin.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../app/helper/logger.php';
zabeleziPristup(); 


$logFajl = __DIR__ . '/../data/pristupi.txt'; 

$statistika = [];
$ukupnoPristupa = 0;
$ulogovaniDanas = [];
$danasnjiDatum = date('Y-m-d');

if (file_exists($logFajl)) {
    $linije = file($logFajl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($linije as $linija) {
        $delovi = explode('||', $linija);
        if (count($delovi) < 4) continue;
        
        $vreme = $delovi[0];      
        $stranica = $delovi[1];   
        $korisnik = $delovi[2];   
        
        $ukupnoPristupa++;
        
        if (!isset($statistika[$stranica])) {
            $statistika[$stranica] = 0;
        }
        $statistika[$stranica]++;
        
        if (strpos($vreme, $danasnjiDatum) === 0 && $korisnik !== 'Gost') {
            $ulogovaniDanas[$korisnik] = true;
        }
    }
}

$brojKorisnikaDanas = count($ulogovaniDanas);

$procentiStranica = [];
if ($ukupnoPristupa > 0) {
    foreach ($statistika as $stranica => $brojPristupa) {
        $procentiStranica[$stranica] = round(($brojPristupa / $ukupnoPristupa) * 100, 2);
    }
}

require '../app/layout/head.php';  
require 'layout/header.php';     
?>

<div class="container mt-5">
    <h1 class="mb-4">Admin panel</h1>
    
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <a href="user.php" class="card text-center p-4 shadow-sm text-decoration-none bg-light fw-bold">👤 Korisnici</a>
        </div>
        <div class="col-md-3">
            <a href="bookings.php" class="card text-center p-4 shadow-sm text-decoration-none bg-light fw-bold">📅 Rezervacije</a>
        </div>

        <div class="col-md-3">
    <a href="add_product.php" class="card text-center p-4 shadow-sm text-decoration-none bg-light fw-bold">📦 Dodaj Proizvod</a>
    </div>
        
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm bg-primary text-white">
                <h6 class="mb-1">Korisnici tekućeg dana</h6>
                <h3 class="fw-bold mb-0"><?php echo $brojKorisnikaDanas; ?></h3>
            </div>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Statistika pristupa stranicama (u %)</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Stranica (Ruta)</th>
                        <th>Broj poseta</th>
                        <th>Procenat prisutnosti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($procentiStranica)): ?>
                        <?php foreach ($procentiStranica as $stranica => $procenat): ?>
                            <tr>
                                <td><code><?php echo htmlspecialchars($stranica); ?></code></td>
                                <td><?php echo $statistika[$stranica]; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 fw-bold"><?php echo $procenat; ?>%</span>
                                        <div class="progress w-100" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $procenat; ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Nema zabeleženih pristupa u fajlu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'layout/footer.php'; ?>