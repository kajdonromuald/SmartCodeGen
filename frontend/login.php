<?php

require 'src/JWT.php';
require 'src/Key.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();

// Adatbázis kapcsolat létrehozása
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartcodegen";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $stored_password);
        $stmt->fetch();

        if ($password === $stored_password) {
            // Bejelentkezés sikeres
            $secret_key = "your_secret_key";
            $issuer_claim = "localhost"; 
            $audience_claim = "localhost"; 
            $issuedat_claim = time(); 
            $notbefore_claim = $issuedat_claim; 
            $expire_claim = $issuedat_claim + 3600; 
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $id,
                    "username" => $username
                )
            );

            $jwt = JWT::encode($token, $secret_key, 'HS256');
            $_SESSION['jwt'] = $jwt;
            header("Location: main.html");
            exit();
        } else {
            $error_message = "Hibás jelszó.";
        }
    } else {
        $error_message = "Nincs ilyen felhasználó.";
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Bejelentkezés</h1>
        <form id="form" action="login.php" method="POST">
            <div>
                <label for="email-input">
                    <span>@</span>
                </label>
                <input type="email" name="email" id="email-input" placeholder="Email" required>
            </div>
            <div>
                <label for="password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
                </label>
                <input type="password" name="password" id="password-input" placeholder="Jelszó" required>
            </div>
            <button type="submit">Bejelentkezés</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div class="forgot-password">
            <a href="reset-password.php">Elfelejtett jelszó?</a>
        </div>
        <p>Új vagy itt? <a href="signup.php">Fiók létrehozása</a></p>
    </div>
    <div class="welcome-text">Üdvözöljük újra</div>
</body>
</html>