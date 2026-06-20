document.addEventListener("DOMContentLoaded", function() {
    const counter = document.getElementById('clientCounter');
    if (!counter) return; // proveri da li element postoji

    const target = 500;
    let count = 0;
    const speed = 2000; // trajanje animacije u ms

    const stepTime = Math.abs(Math.floor(speed / target));

    const timer = setInterval(() => {
        count++;
        counter.textContent = count;
        if(count >= target) clearInterval(timer);
    }, stepTime);

});




