<?php
require 'admin.php'; 
require '../app/confing/database.php'; 

$poruka = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naziv = trim($_POST['naziv']);
    $cena = floatval($_POST['cena']);
    $opis = trim($_POST['opis']);
    if (isset($_FILES['slika']) && $_FILES['slika']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['slika']['tmp_name'];
        $fileName = $_FILES['slika']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $dozvoljene_ekstenzije = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($fileExtension, $dozvoljene_ekstenzije)) {
            $novoImeSlike = time() . '_' . uniqid() . '.' . $fileExtension;
            $putanjaOriginal = __DIR__ . '/../assets/images/originals/' . $novoImeSlike;
            $putanjaThumbnail = __DIR__ . '/../assets/images/thumbnails/' . $novoImeSlike;
            if (move_uploaded_file($fileTmpPath, $putanjaOriginal)) {
                if ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
                    $izvornaSlika = imagecreatefromjpeg($putanjaOriginal);
                } elseif ($fileExtension === 'png') {
                    $izvornaSlika = imagecreatefrompng($putanjaOriginal);
                } elseif ($fileExtension === 'webp') {
                    $izvornaSlika = imagecreatefromwebp($putanjaOriginal);
                }
                
                if ($izvornaSlika) {
                    $sirinaOrig = imagesx($izvornaSlika);
                    $visinaOrig = imagesy($izvornaSlika);
                    $novaSirina = 300;
                    $novaVisina = floor($visinaOrig * ($novaSirina / $sirinaOrig));
                    $thumbnailSlika = imagecreatetruecolor($novaSirina, $novaVisina);

                    imagecopyresampled($thumbnailSlika, $izvornaSlika, 0, 0, 0, 0, $novaSirina, $novaVisina, $sirinaOrig, $visinaOrig);

                    if ($fileExtension === 'jpg' || $fileExtension === 'jpeg') {
                        imagejpeg($thumbnailSlika, $putanjaThumbnail, 85);
                    } elseif ($fileExtension === 'png') {
                        imagepng($thumbnailSlika, $putanjaThumbnail, 6);
                    } elseif ($fileExtension === 'webp') {
                        imagewebp($thumbnailSlika, $putanjaThumbnail, 85);
                    }
                    imagedestroy($izvornaSlika);
                    imagedestroy($thumbnailSlika);
                }
                

                $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sdss", $naziv, $cena, $opis, $novoImeSlike);
                
                if ($stmt->execute()) {
                    $poruka = "Proizvod i slike (Original + Thumbnail) su uspešno sačuvani!";
                    $status = "success";
                } else {
                    $poruka = "Greška pri upisu u bazu.";
                    $status = "danger";
                }
                $stmt->close();
                
            } else {
                $poruka = "Greška pri premeštanju originalne slike.";
                $status = "danger";
            }
        } else {
            $poruka = "Format slike nije dozvoljen. Koristite JPG, PNG ili WEBP.";
            $status = "danger";
        }
    } else {
        $poruka = "Molimo izaberite validnu sliku.";
        $status = "danger";
    }
}

require '../app/layout/head.php';
require 'layout/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h2 class="mb-4 text-center">Dodaj novi proizvod (Sa kreiranjem Thumbnail-a)</h2>
                
                <?php if (!empty($poruka)): ?>
                    <div class="alert alert-<?= $status ?>"><?= $poruka ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Naziv proizvoda</label>
                        <input type="text" name="naziv" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cena (RSD)</label>
                        <input type="number" name="cena" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opis proizvoda</label>
                        <textarea name="opis" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Izaberi sliku proizvoda</label>
                        <input type="file" name="slika" class="form-control" required>
                        <small class="text-muted">Sajt će automatski napraviti malu (thumbnail) i veliku verziju slike.</small>
                    </div>
                    <button class="btn btn-success w-100">Sačuvaj proizvod</button>
                </form>
                <div class="text-center mt-3">
                    <a href="index.php">Nazad na Admin Panel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'layout/footer.php'; ?>