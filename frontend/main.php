<?php

session_start();
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

$secret_key = "your_secret_key";
$issuer_claim = "localhost";

if (!isset($_SESSION['jwt'])) {
    header("Location: login.php");
    exit();
}

$username = null;

try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $username = $decoded->data->username;

} catch (ExpiredException $e) {
    session_destroy();
    header("Location: login.php");
    exit();
} catch (SignatureInvalidException $e) {
    session_destroy();
    header("Location: login.php");
    exit();
} catch (BeforeValidException $e) {
    session_destroy();
    header("Location: login.php");
    exit();
} catch (Exception $e) {
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40pylzVz50K92uP5b1z1L707z7eF2/Xn5L1n0r40c1t2/fQ77b7gA2LwNq2FjD1L0V4y9n1a/wBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <li><a href="logout.php">Kijelentkezés</a></li>
                </ul>
            </nav>
           <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button> <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
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
                    <a href="https://www.facebook.com/romuald.kajdon" class="social-icon">F</a>
                    <a href="https://https://github.com/kajdonromuald" class="social-icon">G</a>
                    <a href="https://www.linkedin.com/in/kajdon-romuald-115193351" class="social-icon">L</a>
                   <a href="mailto:kajdon.r@gmail.com" class="social-icon">M</a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Gyorslinkek</h3>
                 <ul>
                <li><a href="index.php">Kódgenerálás</a></li>
                <li><a href="how-it-works.php">Hogyan működik?</a></li>
                <li><a href="logout.php">Kijelentkezés</a></li>
                <li><a href="portfolio.php">Portfólió</a></li>
                <li><a href="privacy.php">Adatvédelmi Nyilatkozat</a></li>
                <li><a href="terms.php">Felhasználási Feltételek</a></li>
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
    <script src="js/theme.js"></script> 
    </body>
</html>