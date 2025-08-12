<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Adatvédelmi Nyilatkozat - SmartCodeGen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/privacy.css">
      <link rel="stylesheet" href="fontawesome-free-7.0.0-web/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="header-content">
            <a href="main.php" class="logo">SmartCodeGen</a>
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="main.php">Kezdőlap</a></li>
                    <li><a href="index.php">Chat (Kódgenerálás)</a></li>
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="contact.php">Kapcsolat</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <?php if (isset($_SESSION['jwt'])): ?>
                        <li><a href="logout.php">Kijelentkezés</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Belépés</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button>
            <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>
    <div class="content-container">
        <h1>Adatvédelmi Nyilatkozat</h1>
        <p>A weboldalunk a GDPR (Általános Adatvédelmi Rendelet) és a vonatkozó jogszabályoknak megfelelően kezeli a felhasználói adatokat. Az alábbiakban részletezzük, hogy milyen adatokat gyűjtünk, hogyan használjuk fel őket, és milyen jogai vannak a felhasználóknak az adataik kezelésével kapcsolatban.</p>
        
        <h2>1. Adatkezelő</h2>
        <p>A SmartCodeGen projekt keretében gyűjtött adatok kezelője: [Neved vagy Cégneved].</p>

        <h2>2. Gyűjtött adatok</h2>
        <p>Regisztráció során a következő adatokat gyűjtjük:</p>
        <ul>
            <li>E-mail cím: A felhasználók azonosítására és a fiókkezelésre szolgál.</li>
            <li>Jelszó: Titkosított formában tároljuk, a felhasználó biztonságos bejelentkezésének érdekében.</li>
        </ul>
        <p>A chat használata során a következő adatokat gyűjtjük:</p>
        <ul>
            <li>Felhasználói kérések és az AI válaszai: Ezeket az adatokat az AI válaszainak kontextusban tartásához és a felhasználói élmény javításához használjuk.</li>
            <li>Visszajelzések (like/dislike): A modell teljesítményének monitorozására és finomítására szolgálnak.</li>
        </ul>

        <h2>3. Adatkezelés célja</h2>
        <p>Az adatokat kizárólag a szolgáltatás nyújtásához, a felhasználói élmény személyre szabásához, a rendszer fejlesztéséhez és a felhasználókkal való kommunikációhoz használjuk.</p>
        
        <h2>4. Adatok megosztása harmadik féllel</h2>
        <p>Az adatokat nem adjuk el, nem adjuk bérbe és nem osztjuk meg harmadik féllel, kivéve, ha erre jogszabály kötelez bennünket, vagy a felhasználó előzetes hozzájárulását adta.</p>
        
        <h2>5. A felhasználó jogai</h2>
        <p>A felhasználóknak joguk van az adatokhoz való hozzáféréshez, azok módosításához, törléséhez vagy az adatkezelés korlátozásához. E jogok gyakorlásához kérjük, vegye fel velünk a kapcsolatot a [e-mail címed] címen.</p>

        <h2>6. Cookie-k</h2>
        <p>A weboldal nem használ sütiket (cookie-kat).</p>
    </div>
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>SmartCodeGen</h3>
                <p>Intelligens kódtámogató eszköz, amely segít gyorsabban és hatékonyabban fejleszteni, különböző programnyelveken.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/romuald.kajdon" class="social-icon">F</a>
                    <a href="https://twitter.com/felhasználóneved" class="social-icon">T</a>
                    <a href="https://www.linkedin.com/in/kajdon-romuald-115193351" class="social-icon">L</a>
                    <a href="mailto:kajdon.romuald@gmail.com" class="social-icon">G</a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Gyorslinkek</h3>
                <ul>
                    <li><a href="index.php">Kódgenerálás</a></li>
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                    <li><a href="privacy.php">Adatvédelmi Nyilatkozat</a></li>
                    <li><a href="terms.php">Felhasználási Feltételek</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Kapcsolat</h3>
                <p>Email: info@smartcodegen.com</p>
                <p>Cím: Budapest, Magyarország</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> SmartCodeGen. Minden jog fenntartva.</p>
        </div>
    </footer>
    <script src="js/navbar.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>