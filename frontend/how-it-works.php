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
    // A kód futása tovább folytatódhat.
    
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
    <title>SmartCodeGen - Hogyan működik?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
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
                    <li><a href="how-it-works.php" class="active">Hogyan működik?</a></li>
                    <li><a href="contact.php">Kapcsolat</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                </ul>
            </nav>
        <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button>   <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>

    <div class="hero-section">
        <div class="container">
            <h1>Hogyan működik a SmartCodeGen? 🧠</h1>
            <p>A SmartCodeGen egy mesterséges intelligencia alapú platform, melynek célja, hogy leegyszerűsítse a programozást és a fejlesztési feladatokat. Az alábbiakban bemutatjuk, hogyan működik a kulisszák mögött, és hogyan használhatod a leghatékonyabban.</p>
        </div>
    </div>
    
    <div class="main-content-section">
        <div class="container">
            <h2>1. A mögöttes technológia: A mesterséges intelligencia ereje</h2>
            <p>A SmartCodeGen a legmodernebb **nagy nyelvi modelleket (LLM-eket)** használja, melyeket hatalmas mennyiségű kódon és szöveges adaton képeztek. Amikor beírsz egy kérést a chatbe, a modell elemzi a szöveget, megérti a szándékodat, majd a tanult minták és logikák alapján generálja a választ. Ez a technológia lehetővé teszi, hogy:</p>
            <ul>
                <li><strong>Kódrészleteket generálj</strong> szinte bármilyen programnyelven.</li>
                <li><strong>Magyarázatot kérj</strong> bonyolult algoritmusokhoz vagy kódrészletekhez.</li>
                <li><strong>Hibákat keress</strong> a meglévő kódodban és javaslatokat kapj a javításra.</li>
                <li><strong>Átalakíts kódot</strong> egyik nyelvről a másikra.</li>
            </ul>

            <br>

            <h2>2. Tippek a hatékony használathoz</h2>
            <p>Az AI-tól kapott legjobb válaszok érdekében fontos, hogy a kérdéseidet a lehető legpontosabban fogalmazd meg. Íme néhány tipp:</p>
            <ul>
                <li><strong>Légy specifikus:</strong> Ahelyett, hogy azt írnád, "írj egy kódot", inkább azt mondd, hogy "írj egy Python kódot, ami beolvas egy CSV fájlt és grafikont készít belőle".</li>
                <li><strong>Add meg a kontextust:</strong> Mesélj az AI-nak a projekt céljáról, a használt programnyelvről és a kívánt kimeneti formátumról.</li>
                <li><strong>Kérdezz rá a hibákra:</strong> Ha egy kód nem működik, másold be a kódot és a hozzá tartozó hibaüzenetet is.</li>
                <li><strong>Kérj részletes magyarázatot:</strong> Használd a "magyarázd el lépésről lépésre" vagy a "kommenteld a kódot" kifejezéseket, ha jobban meg szeretnéd érteni a megoldást.</li>
            </ul>

            <br>
            
            <h2>3. Előnyök és korlátok</h2>
            <p>A SmartCodeGen hatalmas segítség lehet a mindennapi fejlesztésben, de fontos tisztában lenni a korlátaival is:</p>
            <ul>
                <li><strong>Sebesség és hatékonyság:</strong> Az AI segítségével percek alatt elkészítheted azokat a feladatokat, amelyek korábban órákba teltek volna.</li>
                <li><strong>Tanulási lehetőség:</strong> Az AI-tól kapott kódok és magyarázatok segítségével gyorsabban tanulhatsz új nyelveket és technológiákat.</li>
                <li><strong>Korlátok:</strong> Az AI által generált tartalom nem minden esetben tökéletes. Mindig ellenőrizd a generált kódot, és győződj meg róla, hogy megfelelően működik, mielőtt éles környezetben használnád.</li>
            </ul>
        </div>
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
                    <li><a href="how-it-works.php" class="active">Hogyan működik?</a></li>
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
    <script src="js/theme.js"></script> 
</body>
</html>