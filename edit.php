<?php
require __DIR__ . '/includes/db.php';
include 'includes/loged_in_check.php';

$fileId = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'];
$canEdit = false;

// Prüfen: Ist der User der Besitzer?
$stmt = $conn->prepare("SELECT filename, filepath FROM files WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $fileId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filePath = $row['filepath'];
    $filename = htmlspecialchars($row['filename']);
    $canEdit = true; // Besitzer darf immer editieren
} else {
    // Prüfen: Wurde Datei geteilt?
    $stmt = $conn->prepare("
        SELECT f.filename, f.filepath, fs.permission
        FROM file_shares fs
        JOIN files f ON fs.file_id = f.id
        WHERE fs.file_id = ? AND fs.shared_with_user_id = ?
    ");
    $stmt->bind_param("ii", $fileId, $userId);
    $stmt->execute();
    $shareResult = $stmt->get_result();

    if ($row = $shareResult->fetch_assoc()) {
        $filePath = $row['filepath'];
        $filename = htmlspecialchars($row['filename']);
        $canEdit = ($row['permission'] === 'write');
    } else {
        die("❌ Keine Berechtigung für diese Datei.");
    }
}

// prüfen, ob Datei existiert
if (!file_exists($filePath)) {
    die("❌ Datei existiert nicht mehr.");
}

// Änderungen speichern nur, wenn Edit-Recht vorhanden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($canEdit) {
        $newContent = $_POST['content'] ?? '';
        file_put_contents($filePath, $newContent);
        echo "<p style='color:green;'>✅ Datei gespeichert</p>";
    } else {
        echo "<p style='color:red;'>❌ Keine Schreibrechte</p>";
    }
}

// Datei-Inhalt laden
$content = htmlspecialchars(file_get_contents($filePath));
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bearbeiten: <?= $filename ?></title>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="content">
        <h2>Datei: <?= $filename ?></h2>

        <form method="post" id="editForm">
            <textarea name="content" id="contentArea" rows="20" cols="80" <?= $canEdit ? "" : "readonly" ?>><?= $content ?></textarea><br>
            <?php if ($canEdit): ?>
                <button type="submit" id="saveBtn">Speichern</button>
            <?php endif; ?>
        </form>

        
    </div>

    <script>
    let originalContent = document.getElementById('contentArea').value;
    let isDirty = false;

    // Prüfen, ob Inhalt verändert wurde
    document.getElementById('contentArea').addEventListener('input', function() {
        isDirty = (this.value !== originalContent);
    });

    // Warnung beim Verlassen der Seite (egal ob Navbar-Link, Reload oder Tab schließen)
    window.addEventListener('beforeunload', function (e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Falls Formular gespeichert wird, Dirty-Flag zurücksetzen
    document.getElementById('editForm').addEventListener('submit', function() {
        isDirty = false;
    });
    </script>
</body>
</html>
