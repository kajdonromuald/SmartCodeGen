<?php
// Hibák megjelenítése
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Adatbázis kapcsolat létrehozása
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "coworkly";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kapcsolat ellenőrzése
    if ($conn->connect_error) {
        die("Kapcsolódási hiba: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generálunk egy egyedi jelszó visszaállítási tokent
        $token = bin2hex(random_bytes(50));

        //Token mentése az adatbázisba
        $sql = "UPDATE users SET reset_token=?, reset_token_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);

        if ($stmt->execute()) {
            $success_message = "Sikeresen elküldtük az e-mailt a jelszó visszaállításához.";
        } else {
            $error_message = "Nem sikerült frissíteni a visszaállítási tokent. Kérjük, próbáld újra.";
        }
    } else {
        $error_message = "Nem található ilyen e-mail cím.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítása</title>
    <link rel="stylesheet" href="css/reset-password.css">
    <script type="text/javascript" src="js/validation.js" defer></script>
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="reset-password.php" method="post">
        <h1>Reset Password</h1>
        <p id="error-message"></p>
        <p>Add meg az e-mail címedet, és küldünk neked egy linket a jelszavad visszaállításához.</p>
        
        <form id="reset-form">
            <div>
                <label for="email-input">
                    <span>@</span>
                </label>
                <input type="email" name="email" id="email-input" placeholder="Email" required>
            </div>
            
            <button type="submit">Küldés</button>
            <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        </form>
        </form>

        <p><a href="login.php">Vissza a Bejelentkezéshez</a></p>
    </div>
</body>
</html>