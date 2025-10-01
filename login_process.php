<?php
session_start();
require __DIR__ . '/includes/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = trim($_POST['username_email']);
    $password = trim($_POST['password']);

    // Nutzer suchen
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Passwort überprüfen
        if (password_verify($password, $user['password'])) {
            // Login erfolgreich
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "❌ Falsches Passwort!";
        }
    } else {
        echo "❌ Benutzer nicht gefunden!";
    }
}
?>
