<?php
// C:\xampp\htdocs\SmartCodeGen\frontend\index.php

session_start();

// A projekt gyökérkönyvtára (egy szinttel feljebb a 'frontend' mappától)
define('ROOT_PATH', dirname(__DIR__));

// Composer autoloader betöltése (fontos, hogy ez legyen az első!)
require_once ROOT_PATH . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

// A secret_key-nek meg kell egyeznie a login.php-ban használt kulccsal!
$secret_key = "your_secret_key";
$issuer_claim = "localhost";

// Ellenőrizzük, hogy van-e JWT a sessionben
if (!isset($_SESSION['jwt'])) {
    header("Location: login.php");
    exit();
}

// Megpróbáljuk dekódolni és ellenőrizni a JWT tokent
try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    
    $user_id = $decoded->data->id;

} catch (Exception $e) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// *** ÚJ KÓD: Beszélgetési azonosító (conversation_id) kezelése ***
// Ellenőrizzük, hogy létezik-e már conversation_id a sessionben.
// Ha nem, generálunk egy újat, ami az egész beszélgetésre érvényes lesz.
if (!isset($_SESSION['conversation_id'])) {
    $_SESSION['conversation_id'] = uniqid('conv_', true);
}
$conversation_id = $_SESSION['conversation_id'];


// Adatbázis kapcsolat létrehozása a db_config.php fájllal
require_once 'db_config.php';

// Dotenv betöltése a .env fájl olvasásához
try {
    $dotenv = Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
    $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;
    if (empty($apiKey)) {
        die("Hiba: Az AI API kulcs nem található. Kérjük, ellenőrizze a .env fájl-t.");
    }
} catch (Dotenv\Exception\InvalidPathException $e) {
    die("A rendszer konfigurációs fájlja nem olvasható. Kérjük, értesítse a rendszergazdát.");
} catch (Exception $e) {
    die("Hiba történt a környezeti beállítások betöltésekor.");
}

// Ellenőrizzük, hogy a cURL kiterjesztés engedélyezve van-e
if (!function_exists('curl_init')) {
    // Itt valószínűleg egy hibaoldalt kéne mutatni a felhasználónak,
    // vagy megszakítani a futást, mert az AI funkció nem fog működni.
}

// AI API HÍVÁS LOGIKA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_message'])) {
    $userMessage = $_POST['user_message'];

    try {
        // Gemini API végpont és kulcs
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key=" . $apiKey;

        // *** MÓDOSÍTOTT KÓD: Kontextus lekérése az adatbázisból és helyes formázás nélkül Gemini-nek küldése ***
        $context = [];
        $sql_context = "SELECT prompt, response FROM ai_logs WHERE conversation_id = :conversation_id ORDER BY timestamp ASC";
        $stmt_context = $pdo->prepare($sql_context);
        $stmt_context->bindParam(':conversation_id', $conversation_id);
        $stmt_context->execute();
        $previous_messages = $stmt_context->fetchAll(PDO::FETCH_ASSOC);

        foreach ($previous_messages as $message) {
            $context[] = ['role' => 'user', 'parts' => ['text' => $message['prompt']]];
            $context[] = ['role' => 'model', 'parts' => ['text' => $message['response']]];
        }
        
        // Az új prompt hozzáadása a kontextus végére
        $context[] = ['role' => 'user', 'parts' => ['text' => $userMessage]];
        
        // Adatcsomag összeállítása a teljes kontextussal
        $data = [
            'contents' => $context
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Hálózati hiba: " . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
        $aiResponseText = "";
        $error = false;

        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $aiResponseText = $responseData['candidates'][0]['content']['parts'][0]['text'];
        } else if (isset($responseData['error'])) {
            // Speciális hibaüzenetek az AI API-tól
            $errorCode = $responseData['error']['code'] ?? null;
            $errorMessage = $responseData['error']['message'] ?? 'Ismeretlen hiba történt.';

            switch ($errorCode) {
                case 429:
                    $aiResponseText = "Az AI API kéréslimitje elérte a határt. Kérjük, próbálja újra később.";
                    break;
                case 400:
                    $aiResponseText = "Az AI API nem tudta feldolgozni a kérését. Valószínűleg a kérés tartalma nem megfelelő. Kérjük, fogalmazza meg újra.";
                    break;
                default:
                    $aiResponseText = "Az AI API hibaüzenetet küldött: " . $errorMessage;
                    break;
            }
            $error = true;
        } else {
            $aiResponseText = "Az AI nem tudott releváns választ adni, vagy ismeretlen hiba történt.";
            $error = true;
        }

        // *** MÓDOSÍTOTT KÓD: Adatbázisba mentés a conversation_id-val és a nyers, formázatlan szöveggel ***
        try {
            $sql = "INSERT INTO ai_logs (user_id, prompt, response, conversation_id) VALUES (:user_id, :prompt, :response, :conversation_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':prompt', $userMessage);
            $stmt->bindParam(':response', $aiResponseText);
            $stmt->bindParam(':conversation_id', $conversation_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Hiba az adatbázisba mentéskor: " . $e->getMessage());
        }

        // *** MÓDOSÍTOTT KÓD: A válasz HTML formázása a frontendnek elküldés előtt ***
        $formattedResponse = preg_replace_callback('/```(\w+)?(.*?)```/s', function ($matches) {
            // Ha PHP nyelv van megadva, használjuk a "clike" nyelvet helyette
            // Így elkerülhető a Prism.js hiba
            $language = (!empty($matches[1]) && strtolower($matches[1]) !== 'php') ? htmlspecialchars($matches[1]) : 'clike';
            $code = trim($matches[2]);
            if (empty($code)) {
                return '';
            }
            return '<pre><code class="language-' . $language . '">' . htmlspecialchars($code) . '</code></pre>';
        }, $aiResponseText);

        header('Content-Type: application/json');
        echo json_encode(['status' => $error ? 'error' : 'success', 'message' => $formattedResponse]);
        exit();

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Szerveroldali hiba történt: ' . $e->getMessage()]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>SmartCodeGen - Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-coy.min.css" rel="stylesheet" />
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
    <div class="chat-container">
        <div class="chat-box-container">
            <div class="chat-box" id="chat-box">
                <div class="chat-message ai-text-message">🤖 Üdvözöllek a SmartCodeGen rendszerben! Miben segíthetek?</div>
            </div>
            <form class="chat-input" id="chat-form">
                <textarea id="user-input" placeholder="Írd be az üzeneted..." required></textarea>
                <button type="submit">➤</button>
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
                <p>Cím: Budapest, Magyarország</p>
                <p class="disclaimer">Az AI által generált tartalom helytelen lehet.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> SmartCodeGen. Minden jog fenntartva.</p>
            </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="js/navbar.js"></script>
    <script src="js/chat.js"></script>
</body>
</html>