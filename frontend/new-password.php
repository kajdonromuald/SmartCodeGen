<?php
// Hibák megjelenítése fejlesztői környezetben
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';
$token_valid = false;
$token = $_GET['token'] ?? '';

// Adatbázis kapcsolat
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "smartcodegen";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Token és email validációja
if (!empty($token)) {
    // A token lekérdezése az adatbázisból (prepared statement)
    $sql = "SELECT id, reset_token_expiry, reset_token FROM users"; // JAVÍTVA
    $result = $conn->query($sql);
    $user_id = null;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // A hashelt tokent ellenőrizzük a sima tokennel
            if (password_verify($token, $row['reset_token']) && strtotime($row['reset_token_expiry']) > time()) { // JAVÍTVA
                $user_id = $row['id'];
                $token_valid = true;
                break;
            }
        }
    }
}

// Ha érvényes a token, feldolgozzuk az új jelszót
if ($_SERVER["REQUEST_METHOD"] == "POST" && $token_valid) {
    $new_password = $_POST['new_password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    if (empty($new_password) || empty($repeat_password)) {
        $message = "Mindkét jelszó mező kitöltése kötelező.";
    } elseif (strlen($new_password) < 6) {
        $message = "A jelszónak legalább 6 karakter hosszúnak kell lennie.";
    } elseif ($new_password !== $repeat_password) {
        $message = "A jelszavak nem egyeznek.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Új jelszó mentése (prepared statement) és token törlése
        $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?"; // JAVÍTVA
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $message = "Jelszavad sikeresen megváltoztatva! Most már <a href='login.php'>bejelentkezhetsz.</a>";
            $token_valid = false; // A jelszó megváltoztatása után a tokent érvénytelenítjük
        } else {
            $message = "Hiba történt a jelszó megváltoztatása során.";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új jelszó</title>
    <link rel="stylesheet" href="css/styles.css">
    <script type="text/javascript" src="js/validation.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <h1>Új jelszó beállítása</h1>
        <?php if (!empty($message)): ?>
            <p class="<?php echo ($message === "Jelszavad sikeresen megváltoztatva! Most már <a href='login.php'>bejelentkezhetsz.</a>") ? 'success-message' : 'error-message'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if ($token_valid): ?>
            <form id="new-password-form" action="new-password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                <div>
                    <label for="new_password-input">
                        <span>🔑</span>
                    </label>
                    <input type="password" name="new_password" id="new_password-input" placeholder="Új jelszó" required>
                </div>
                <div>
                    <label for="repeat_password-input">
                        <span>🔑</span>
                    </label>
                    <input type="password" name="repeat_password" id="repeat_password-input" placeholder="Új jelszó megerősítése" required>
                </div>
                <button type="submit">Jelszó megváltoztatása</button>
            </form>
        <?php else: ?>
            <?php if (empty($message)): ?>
                <p class="error-message">A token érvénytelen vagy lejárt.</p>
            <?php endif; ?>
        <?php endif; ?>
        <p>Emlékszel a jelszavadra? <a href="login.php">Jelentkezz be!</a></p>
    </div>
    <div class="welcome-text">
        <span>Új kezdet!</span>
    </div>
</body>
</html>