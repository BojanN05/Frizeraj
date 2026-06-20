<?php
session_start();
require '../app/layout/head.php';
require '../app/layout/header.php';
require_once __DIR__ . '/../app/helper/logger.php';
zabeleziPristup(); 
?>

<div class="container mt-5">
    <h1 class="text-center mb-5">Naši Proizvodi</h1>

    <div class="row g-3 mb-4 bg-light p-3 rounded shadow-sm">
        <div class="col-md-6">
            <input type="text" id="pretragaInput" class="form-control" placeholder="Pretraži proizvode po nazivu...">
        </div>
        <div class="col-md-6">
            <select id="sortiranjeSelect" class="form-select">
                <option value="default">Sortiraj po: Najnovije</option>
                <option value="price_asc">Cena: Od niže ka višoj</option>
                <option value="price_desc">Cena: Od više ka nižoj</option>
            </select>
        </div>
    </div>

    <div class="row g-4" id="proizvodiKontejner">
        </div>

    <nav class="mt-5">
        <ul class="pagination justify-content-center" id="paginacijaKontejner">
            </ul>
    </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const pretragaInput = document.getElementById("pretragaInput");
    const sortiranjeSelect = document.getElementById("sortiranjeSelect");
    const proizvodiKontejner = document.getElementById("proizvodiKontejner");
    const paginacijaKontejner = document.getElementById("paginacijaKontejner");
    let trenutnaStranica = 1;
    function ucitajProizvode() {
        const searchValue = pretragaInput.value;
        const sortValue = sortiranjeSelect.value;
        fetch(`ajax/ajax_proizvodi.php?search=${encodeURIComponent(searchValue)}&sort=${sortValue}&page=${trenutnaStranica}`)
            .then(response => response.json())
            .then(data => {
                proizvodiKontejner.innerHTML = "";
                
                if (data.proizvodi.length === 0) {
                    proizvodiKontejner.innerHTML = '<div class="col-12 text-center text-muted"><p>Nema pronađenih proizvoda.</p></div>';
                } else {
                    data.proizvodi.forEach(proizvod => {
                        const htmlKartica = `
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="../assets/images/thumbnails/${proizvod.image}" class="card-img-top" alt="${proizvod.name}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title fw-bold">${proizvod.name}</h5>
                                        <p class="card-text text-muted">${proizvod.description ? proizvod.description : 'Nema opisa.'}</p>
                                        <p class="fw-bold text-primary fs-5">Cena: ${proizvod.price} RSD</p>
                                        <a href="../assets/images/originals/${proizvod.image}" target="_blank" class="btn btn-outline-secondary btn-sm w-100">Prikaži punu rezoluciju</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        proizvodiKontejner.innerHTML += htmlKartica;
                    });
                }
                paginacijaKontejner.innerHTML = "";
                for (let i = 1; i <= data.ukupnoStranica; i++) {
                    const aktivnaKlasa = (i === data.trenutnaStranica) ? 'active' : '';
                    const htmlDugme = `
                        <li class="page-item ${aktivnaKlasa}">
                            <button class="page-link nalog-stranica-btn" data-page="${i}">${i}</button>
                        </li>
                    `;
                    paginacijaKontejner.innerHTML += htmlDugme;
                }
                document.querySelectorAll(".nalog-stranica-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        trenutnaStranica = parseInt(this.getAttribute("data-page"));
                        ucitajProizvode();
                    });
                });
            })
            .catch(error => console.error("Greška pri učitavanju:", error));
    }
    pretragaInput.addEventListener("input", function() {
        trenutnaStranica = 1; 
        ucitajProizvode();
    });

    sortiranjeSelect.addEventListener("change", function() {
        trenutnaStranica = 1;
        ucitajProizvode();
    });
    ucitajProizvode();
});
</script>

<?php 
require '../app/layout/footer.php'; 
require '../app/layout/scripts.php'; 
?>