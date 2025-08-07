<?php
// Hibák megjelenítése
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ellenőrizd, hogy ez az útvonal helyes!
require dirname(__DIR__) . '/frontend/vendor/autoload.php';

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    // Server-side validáció
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Kérjük, adj meg egy érvényes e-mail címet.";
    } else {
        // Adatbázis kapcsolat létrehozása
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "smartcodegen";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Kapcsolat ellenőrzése
        if ($conn->connect_error) {
            die("Kapcsolódási hiba: " . $conn->connect_error);
        }

        // Ellenőrizzük, hogy az e-mail létezik-e (prepared statement)
        $sql = "SELECT id FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $user = $result->fetch_assoc();

        if ($user) {
            // Generálunk egy egyedi, biztonságos token-t
            $token = bin2hex(random_bytes(50));
            $hashed_token = password_hash($token, PASSWORD_DEFAULT); // A token hashelése!

            // A hashelt token mentése az adatbázisba
            $sql = "UPDATE users SET reset_token=?, reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_token, $email); // A hashelt tokent tároljuk

            if ($stmt->execute()) {
                $mail = new PHPMailer(true);

                try {
                    // SMTP beállítások
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kajdon.r@gmail.com'; 
                    $mail->Password = 'qusu kqxr zrxs huna';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    // Címzettek
                    $mail->setFrom('no-reply@smartcodegen.hu', 'SmartCodeGen'); // Javítottuk a domain-t
                    $mail->addAddress($email);

                    // E-mail tartalom
                    $mail->isHTML(true);
                    $mail->Subject = 'Jelszo visszaallitas - SmartCodeGen';
                    
                    // Létrehozzuk a jelszó-visszaállítási linket
                    $reset_link = "http://localhost/SmartCodeGen/frontend/new-password.php?token=" . urlencode($token);

                    $mail->Body = '
                    <!DOCTYPE html>
                    <html lang="hu">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Jelszó visszaállítása</title>
                        <style>
                            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
                            .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                            .header { background-color: #8672FF; color: #ffffff; text-align: center; padding: 20px; font-size: 24px; font-weight: bold; }
                            .content { padding: 20px; color: #333333; line-height: 1.6; }
                            .button-container { text-align: center; margin-top: 30px; margin-bottom: 30px; }
                            .button { display: inline-block; background-color: #8672FF; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px; }
                            .footer { background-color: #f4f4f4; color: #666666; text-align: center; padding: 20px; font-size: 12px; }
                            .footer a { color: #8672FF; text-decoration: none; }
                            .disclaimer { font-size: 10px; color: #999999; margin-top: 20px; }
                        </style>
                    </head>
                    <body>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto; width: 100%; max-width: 600px;">
                            <tr>
                                <td align="center" style="padding: 0;">
                                    <div class="container">
                                        <div class="header">SmartCodeGen</div>
                                        <div class="content">
                                            <h1>Jelszó-visszaállítási kérelem</h1>
                                            <p>Kedves Felhasználó!</p>
                                            <p>Ezt az e-mailt azért kapta, mert jelszó-visszaállítást kért a SmartCodeGen rendszerben.</p>
                                            <p>Kérjük, kattintson az alábbi gombra a jelszava megváltoztatásához:</p>
                                            <div class="button-container">
                                                <a href="' . $reset_link . '" class="button">Új jelszó beállítása</a>
                                            </div>
                                            <p>A link 1 órán belül érvényes.</p>
                                            <p>Ha nem Ön kérte a jelszó-visszaállítást, kérjük, hagyja figyelmen kívül ezt az e-mailt.</p>
                                        </div>
                                        <div class="footer">
                                            <p>&copy; ' . date("Y") . ' SmartCodeGen. Minden jog fenntartva.</p>
                                            <p class="disclaimer">Ez egy automatikusan generált e-mail, kérjük, ne válaszoljon rá.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </body>
                    </html>
                    ';
                    $mail->send();
                    $success_message = "Sikeresen elküldtük az e-mailt a jelszó visszaállításához. Kérlek, ellenőrizd a bejövő leveleidet (és a spam mappát is)!";
                } catch (Exception $e) {
                    $error_message = "Nem sikerült elküldeni a visszaállító e-mailt. Kérlek, ellenőrizd az e-mail beállításokat. Hiba: " . $mail->ErrorInfo;
                }
            } else {
                $error_message = "Nem sikerült frissíteni a visszaállítási tokent az adatbázisban. Kérjük, próbáld újra.";
            }
        } else {
            $error_message = "Nem található ilyen e-mail cím.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="css/styles.css">
    <script type="text/javascript" src="js/validation.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <h1>Jelszó visszaállítása</h1>
        
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <form id="reset-form" action="reset-password.php" method="post">
            <div>
                <label for="email-input">
                    <span>@</span>
                </label>
                <input type="email" name="email" id="email-input" placeholder="Email" required>
            </div>
            
            <button type="submit">Küldés</button>
        </form>
        <p>Emlékszel a jelszavadra? <a href="login.php">Jelentkezz be!</a></p>
    </div>
    <div class="welcome-text">
        <span>Elfelejtetted?</span>
    </div>
</body>
</html>