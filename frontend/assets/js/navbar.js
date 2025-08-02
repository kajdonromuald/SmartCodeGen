// C:\xampp\htdocs\SmartCodeGen\js\navbar.js

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
        });

        // Opcionális: Bezárja a menüt, ha valahova máshova kattintunk
        document.addEventListener('click', function(event) {
            if (!mainNav.contains(event.target) && !menuToggle.contains(event.target) && mainNav.classList.contains('active')) {
                mainNav.classList.remove('active');
            }
        });
    }

    // 'active' osztály hozzáadása az aktuális oldal linkjéhez
    // A currentPath beállítása módosítva lett, hogy a main.php is megfelelően kezelve legyen
    const currentPath = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.main-nav .nav-list li a');
    navLinks.forEach(link => {
        const linkPath = link.href.split('/').pop();
        // Ellenőrizzük, hogy az aktuális oldal linkjére rákerüljön az 'active' osztály
        // Ha a currentPath üres (gyökér URL), és a link a 'main.php'-ra mutat, akkor az is aktív legyen,
        // vagy ha pontosan megegyezik a fájlnév
        if (currentPath === linkPath || (currentPath === '' && linkPath === 'main.php')) {
            link.classList.add('active');
        }
    });
});