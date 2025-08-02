<?php

session_start();

// A JWT ellenőrzéshez szükséges könyvtárak
require 'src/JWT.php';
require 'src/Key.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// A secret_key-nek meg kell egyeznie a login.php-ban használt kulccsal!
$secret_key = "your_secret_key";
$issuer_claim = "localhost";

// Ellenőrizzük, hogy van-e JWT a sessionben
if (!isset($_SESSION['jwt'])) {
    // Ha nincs, átirányítjuk a felhasználót a bejelentkezési oldalra
    header("Location: login.php");
    exit();
}

// Ha van, megpróbáljuk dekódolni és ellenőrizni
try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

    // A JWT érvényes, a felhasználó be van jelentkezve.
    // Itt a $decoded->data->username tartalmazza a felhasználó nevét.
    // Ezt felhasználhatod a köszöntő üzenetek testreszabására.
    $username = $decoded->data->username;
    
} catch (Exception $e) {
    // A JWT nem érvényes (lejárt, hibás aláírás, stb.).
    // Töröljük a sessiont és átirányítjuk a bejelentkezési oldalra.
    session_destroy();
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
 <meta charset="UTF-8">
 <title>SmartCodeGen - Főoldal</title>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
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
                    <li><a href="logout.php">Kijelentkezés</a></li>
                </ul>
            </nav>
            <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>

    <div class="hero-section">
         <h1>Üdvözöljük, <?php echo htmlspecialchars($username); ?>!</h1>
             <p>A SmartCodeGen egy intelligens segéd a szoftverfejlesztéshez. Segítünk kódrészleteket, funkciókat és egész programokat generálni a te utasításaid alapján, különböző programnyelveken.</p>
             <p>Fejlessz gyorsabban és hatékonyabban, kevesebb gépeléssel!</p>
<a href="index.php" class="hero-button">Kezdj el kódot generálni most!</a>
</div>

  <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>SmartCodeGen</h3>
                <p>Intelligens kódtámogató eszköz, amely segít gyorsabban és hatékonyabban fejleszteni, különböző programnyelveken.</p>
                <div class="social-links">
                    <a href="#" class="social-icon">F</a>
                    <a href="#" class="social-icon">T</a>
                    <a href="#" class="social-icon">L</a>
                    <a href="#" class="social-icon">G</a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Gyorslinkek</h3>
                <ul>
                    <li><a href="index.php">Kódgenerálás</a></li>
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                    <li><a href="#">Adatvédelmi Nyilatkozat</a></li>
                    <li><a href="#">Felhasználási Feltételek</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Kapcsolat</h3>
                <p>Email: info@smartcodegen.com</p>
                <p>Cím: Budapest, Magyarország</p>
                <p class="disclaimer">Az AI által generált tartalom helytelen lehet.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> SmartCodeGen. Minden jog fenntartva.</p>
        </div>
    </footer>

    <script src="js/navbar.js"></script>
    </body>
</html>