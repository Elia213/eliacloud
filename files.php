

<?php
require __DIR__ . '/includes/db.php';
include 'includes/loged_in_check.php';

// Falls Upload durchgeführt wird
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $userId = $_SESSION['user_id'];
    $uploadDir = dirname(__DIR__) . '/uploads/' . $userId . '/';

    // Verzeichnis für den Benutzer anlegen, falls nicht vorhanden
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES['file']['name']);
    $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Nur .txt erlauben
    if ($fileExtension !== 'txt') {
        echo "<p style='color:red;'>❌ Bis jetzt funktionieren nur .txt Dateien, vieleicht kommen noch weitere dazu ;)!</p>";
    } else {
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            // Relativer Pfad für DB
            $dbPath = 'uploads/' . $userId . '/' . $filename;

            // In DB speichern – jetzt mit absolutem Pfad
            $stmt = $conn->prepare("INSERT INTO files (user_id, filename, filepath) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $filename, $targetPath);
            $stmt->execute();

            echo "<p style='color:green;'>✅ Datei erfolgreich hochgeladen</p>";
        } else {
            echo "<p style='color:red;'>❌ Fehler beim Hochladen</p>";
        }
    }
}
?>
<?php include 'includes/navbar.php'; ?>
<div class="content">

<h2>Ihre Dateien</h2>

<!-- Upload-Formular -->
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".txt" required>
    <button type="submit">Hochladen</button>
</form>

<hr>

<!-- Liste der Dateien -->
<ul>
<?php
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, filename, uploaded_at FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $fileId = $row['id'];
    $filename = htmlspecialchars($row['filename']);
    $uploaded = $row['uploaded_at'];

    echo "<li>
        $filename ($uploaded)
        | <a href='download.php?id=$fileId'>Download</a>
        | <a href='edit.php?id=$fileId'>Öffnen</a>
        | <a href='share.php?id=$fileId'>Teilen</a>
        | <a href='delete.php?id=$fileId' onclick=\"return confirm('❌ Datei wirklich löschen?');\">Löschen</a>
        | <a href='permissions.php?id=$fileId'>Berechtigungen</a>
    </li>";
}
?>
 </ul>

<p><a href="dashboard.php">⬅ Zurück zum Dashboard</a></p>

