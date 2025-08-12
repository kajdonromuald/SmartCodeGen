<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Felhasználási Feltételek - SmartCodeGen</title>
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
        <h1>Felhasználási Feltételek</h1>
        <p>A jelen dokumentum a SmartCodeGen weboldal (a továbbiakban: "Szolgáltatás") használatának feltételeit tartalmazza. A Szolgáltatás használatával Ön elfogadja a jelen feltételeket.</p>
        
        <h2>1. A szolgáltatás célja</h2>
        <p>A SmartCodeGen egy intelligens kódtámogató eszköz, amely a felhasználó által megadott feladatok alapján kódgenerálást végez.</p>
        
        <h2>2. Felhasználói felelősség</h2>
        <p>A felhasználó felelős minden olyan tartalomért, amelyet a Szolgáltatásban létrehoz vagy közzétesz. Tilos a Szolgáltatást jogellenes vagy etikátlan célokra használni.</p>
        
        <h2>3. A generált tartalom</h2>
        <p>A generált kód és szöveg felhasználásáért a felelősség a felhasználót terheli. A Szolgáltató nem vállal felelősséget a generált kód működéséért, pontosságáért vagy a belőle származó esetleges károkért.</p>
        
        <h2>4. A szolgáltatás korlátai</h2>
        <p>A Szolgáltató fenntartja a jogot a Szolgáltatás módosítására, szüneteltetésére vagy megszüntetésére előzetes értesítés nélkül.</p>
        
        <h2>5. Szellemi tulajdon</h2>
        <p>A weboldal tartalma, beleértve a logót, a designt és a kódot, a Szolgáltató tulajdona. A generált tartalom szerzői jogai a felhasználót illetik.</p>
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