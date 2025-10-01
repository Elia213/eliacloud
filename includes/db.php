<?php
$host = "localhost";
$user = "root";   // Standard in XAMPP
$pass = "";       // Standard in XAMPP ist kein Passwort
$dbname = "eliacloud";

// Verbindung herstellen
$conn = new mysqli($host, $user, $pass, $dbname);


// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>