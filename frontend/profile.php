<?php

session_start();

require 'vendor/autoload.php';
require_once 'db_config.php'; // A PDO kapcsolat betöltése

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

$secret_key = "your_secret_key";

$user_id = null;
$username = null;
$user_email = null;
$requests = [];
$error_message = '';

if (!isset($_SESSION['jwt'])) {
    header("Location: login.php");
    exit();
}

try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
    $username = $decoded->data->username;

    // Felhasználó email címének lekérése PDO-val
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_email = $stmt->fetchColumn();

    // Korábbi kérések lekérése az AI_LOGS táblából!
    $stmt = $pdo->prepare("SELECT prompt, response, timestamp FROM ai_logs WHERE user_id = ? ORDER BY timestamp DESC");
    $stmt->execute([$user_id]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (ExpiredException $e) {
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
    <title>Felhasználói profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="fontawesome-free-7.0.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile-styles.css">
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
                    <li><a href="profile.php" class="active">Profil</a></li>
                    <li><a href="logout.php">Kijelentkezés</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle" aria-label="Téma váltása"></button>
            <button class="menu-toggle" aria-label="Menü megnyitása">☰</button>
        </div>
    </header>

    <main>
        <div class="hero-section">
            <h1>Szia, <?php echo htmlspecialchars($username); ?>!</h1>
            <p>Üdv a profilodon! Itt szerkesztheted a fiókodat és megnézheted a korábbi kéréseidet.</p>
        </div>

        <div class="profile-container">
            <div class="profile-settings">
                <h2>Fiókadatok módosítása</h2>
                
                <h3>E-mail cím frissítése</h3>
                <form action="update_profile.php" method="POST" class="settings-form">
                    <input type="hidden" name="action" value="update_email">
                    <label for="new_email">Jelenlegi e-mail: <?php echo htmlspecialchars($user_email); ?></label>
                    <input type="email" name="new_email" id="new_email" placeholder="Új e-mail cím" required>
                    <button type="submit">E-mail frissítése</button>
                </form>

                <h3>Jelszó módosítása</h3>
                <form action="update_profile.php" method="POST" class="settings-form">
                    <input type="hidden" name="action" value="update_password">
                    <label for="current_password">Jelenlegi jelszó</label>
                    <input type="password" name="current_password" id="current_password" required>
                    <label for="new_password">Új jelszó</label>
                    <input type="password" name="new_password" id="new_password" required>
                    <label for="confirm_password">Új jelszó megerősítése</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <button type="submit">Jelszó módosítása</button>
                </form>
            </div>

            <div class="profile-history">
                <h2>Korábbi kéréseid</h2>
                <?php if (empty($requests)): ?>
                    <p>Még nincsenek korábbi kéréseid.</p>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                        <div class="request-item">
                            <h4>Kérés: <?php echo htmlspecialchars($req['prompt']); ?></h4>
                            <p>Dátum: <?php echo htmlspecialchars($req['timestamp']); ?></p>
                            <p>Generált kód:</p>
                            <pre><code><?php echo htmlspecialchars($req['response']); ?></code></pre>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
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