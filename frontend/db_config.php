<?php
// Adatbázis csatlakozási adatok
$host = 'localhost';
$dbname = 'smartcodegen';
$username = 'root';
$password = '';

try {
    // PDO kapcsolat létrehozása
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Hibaüzenetek engedélyezése
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Ha a kapcsolat sikertelen, megállítjuk a programot és kiírjuk a hibaüzenetet
    die("Sikertelen kapcsolódás az adatbázishoz: " . $e->getMessage());
}