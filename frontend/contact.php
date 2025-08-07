<?php

session_start();

// A JWT ellenőrzéshez szükséges könyvtárak
require 'src/JWT.php';
require 'src/Key.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// PHPMailer betöltése a Composer autoloader segítségével
// Ellenőrizd, hogy a vendor mappa a frontend mappán belül van-e!
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// A secret_key-nek meg kell egyeznie a login.php-ban használt kulccsal!
$secret_key = "your_secret_key";
$issuer_claim = "localhost";

// Ellenőrizzük, hogy van-e JWT a sessionben
if (!isset($_SESSION['jwt'])) {
    header("Location: login.php");
    exit();
}

try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
} catch (Exception $e) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// === KAPCSOLATFELVÉTELI ŰRLAP FELDOLGOZÓ LOGIKÁJA ===
$status = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\n","\r"),"",$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    $subject = trim($_POST["subject"]);

    if (empty($name) OR empty($message) OR empty($email)) {
        $status = "Kérjük, tölts ki minden mezőt!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "Hibás e-mail cím formátum!";
    } else {
        $recipient = "kajdon.r@gmail.com"; // <-- IDE ÍRD BE A CÉL E-MAIL CÍMEDET!
        
        $mail = new PHPMailer(true);
        try {
            // SMTP beállítások
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kajdon.r@gmail.com'; // <-- IDE ÍRD BE A SAJÁT GMAIL CÍMEDET!
            $mail->Password = 'qusu kqxr zrxs huna'; // <-- IDE ÍRD BE AZ ALKALMAZÁSJELSZAVAT!
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Küldő és fogadó
            $mail->setFrom($email, $name);
            $mail->addAddress($recipient);

            // Tartalom
            $mail->isHTML(false);
            $mail->Subject = "SmartCodeGen űrlap: " . $subject;
            $mail->Body    = "Név: $name\nE-mail: $email\n\nÜzenet:\n$message";

            $mail->send();
            $status = "Köszönjük! Az üzenetedet sikeresen elküldtük.";
        } catch (Exception $e) {
            $status = "Hiba történt. Kérjük, próbáld újra. Hibaüzenet: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>SmartCodeGen - Kapcsolat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/contact.css?v=1.0.1">
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
                    <li><a href="contact.php" class="active">Kapcsolat</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button>
            <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>

    <div class="hero-section" style="min-height: calc(100vh - 80px - 160px - 40px);">
        <h1>Kapcsolatfelvétel</h1>
        <p>Ha bármilyen kérdésed vagy észrevételed van, lépj velünk kapcsolatba az alábbi űrlapon keresztül.</p>
        
        <div class="contact-form-container">
            <?php if (!empty($status)): ?>
                <p class="form-status-message"><?= htmlspecialchars($status); ?></p>
            <?php endif; ?>
            <form id="contact-form" action="contact.php" method="post">
                <div class="form-group">
                    <label for="name">Név</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail cím</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Tárgy</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Üzenet</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="hero-button">Küldés</button>
            </form>
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
                    <li><a href="how-it-works.php">Hogyan működik?</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                    <li><a href="#">Adatvédelmi Nyilatkozat</a></li>
                    <li><a href="#">Felhasználási Feltételek</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Kapcsolat</h3>
                <p>Email: info@smartcodegen.com</p>
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