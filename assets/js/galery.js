const BASE_URL = '/Frizeraj/'; // tvoj podfolder

document.querySelectorAll('.gallery-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const category = this.dataset.category;
        const gallery = document.getElementById('gallery');

        gallery.innerHTML = '<p>Učitavanje...</p>';

        fetch(`${BASE_URL}public/ajax/gallery_fetch.php?category=${encodeURIComponent(category)}`)
            .then(response => {
                if (response.status === 204) {
                    gallery.innerHTML = '<p>Nema slika u ovoj kategoriji.</p>';
                    return [];
                }
                if (!response.ok) {
                    throw new Error('Server error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Primljene slike:', data);
                if (data.length === 0) return;

                gallery.innerHTML = '';
                data.forEach(path => {
                    gallery.innerHTML += `
                        <div style="width: 400px; height: 400px; overflow: hidden; margin: 5px; display: inline-block;">
                            <img src="${BASE_URL}${path}" 
                                 alt="Galerija Slika"
                                 style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    `;
                });
            })
            .catch(err => {
                console.error(err);
                gallery.innerHTML = '<p class="text-danger">Greška pri učitavanju galerije.</p>';
            });
    });
});