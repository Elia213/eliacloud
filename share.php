<?php
session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("❌ Zugriff verweigert");
}

if (!isset($_GET['id'])) {
    die("❌ Keine Datei angegeben");
}

$fileId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// Prüfen, ob die Datei dem User gehört
$stmt = $conn->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $fileId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    die("❌ Keine Berechtigung");
}

$filename = htmlspecialchars($row['filename']);

// Wenn das Formular abgeschickt wird
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shareEmail = trim($_POST['share_with_email']);
    $permission = $_POST['permission'];

    // User anhand E-Mail suchen
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $shareEmail);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userRow = $userResult->fetch_assoc()) {
        $shareWithUserId = $userRow['id'];

        // Prüfen, ob schon geteilt wurde
        $stmt = $conn->prepare("SELECT id FROM file_shares WHERE file_id = ? AND shared_with_user_id = ?");
        $stmt->bind_param("ii", $fileId, $shareWithUserId);
        $stmt->execute();
        $existsResult = $stmt->get_result();

        if ($existsResult->num_rows > 0) {
            echo "<p style='color:orange;'>⚠️ Datei wurde bereits mit $shareEmail geteilt</p>";
        } else {
            // Teilen eintragen
            $stmt = $conn->prepare("INSERT INTO file_shares (file_id, user_id, shared_with_user_id, permission) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $fileId, $userId, $shareWithUserId, $permission);
            $stmt->execute();

            echo "<p style='color:green;'>✅ Datei wurde mit $shareEmail geteilt!</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Kein Benutzer mit dieser E-Mail gefunden</p>";
    }
}
?>

<h2>Datei teilen: <?= $filename ?></h2>

<form method="post">
    <label>E-Mail-Adresse des Benutzers: <input type="email" name="share_with_email" required></label><br>
    <label>Berechtigung:
        <select name="permission">
            <option value="read">Nur Lesen</option>
            <option value="write">Bearbeiten</option>
        </select>
    </label><br>
    <button type="submit">Teilen</button>
</form>

<p><a href="files.php">⬅ Zurück</a></p>

