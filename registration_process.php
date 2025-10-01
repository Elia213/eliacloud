<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . '/includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Prüfen: Passwörter gleich?
    if ($password !== $confirm_password) {
        die("❌ Die Passwörter stimmen nicht überein.");
    }

    // Prüfen: Nutzername oder Email schon vorhanden?
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("❌ Benutzername oder E-Mail ist bereits vergeben.");
    }

    // Passwort hashen
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Neuen Benutzer einfügen
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        // Neu angelegte User-ID holen
        $new_user_id = $stmt->insert_id;

        // Nutzer sofort einloggen
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['username'] = $username;

        // Weiterleitung ins Dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        die("❌ Fehler bei der Registrierung: " . $stmt->error);
    }
} else {
    die("❌ Ungültiger Zugriff!");
}
