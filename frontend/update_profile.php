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

$user_id = null;
try {
    $jwt = $_SESSION['jwt'];
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "smartcodegen";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    // Hiba esetén visszairányítás
    header("Location: profile.php?error=db_error");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    
    // E-mail frissítése
    if ($action === 'update_email') {
        $new_email = trim($_POST['new_email'] ?? '');
        if (!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $new_email, $user_id);
            if ($stmt->execute()) {
                // Sikeres frissítés
                header("Location: profile.php?success=email_updated");
                exit();
            }
        }
        // Sikertelen frissítés
        header("Location: profile.php?error=invalid_email");
        exit();
    }

    // Jelszó frissítése
    if ($action === 'update_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            header("Location: profile.php?error=empty_password_fields");
            exit();
        }

        if ($new_password !== $confirm_password) {
            header("Location: profile.php?error=passwords_not_match");
            exit();
        }

        // Ellenőrizzük a jelenlegi jelszót
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password_from_db);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $hashed_password_from_db)) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_hashed_password, $user_id);
            if ($stmt->execute()) {
                header("Location: profile.php?success=password_updated");
                exit();
            }
        }
        // Sikertelen frissítés
        header("Location: profile.php?error=invalid_current_password");
        exit();
    }
}

$conn->close();

// Ha semmi nem futott le, visszairányítás
header("Location: profile.php?error=invalid_request");
exit();

?>