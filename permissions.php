
<?php
require __DIR__ . '/includes/db.php';
include 'includes/loged_in_check.php';
?>
<?php include 'includes/navbar.php'; ?>
<div class="content">

<?php
if (!isset($_GET['id'])) {
    die("❌ Keine Datei angegeben.");
}

$fileId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// prüfen, ob User Besitzer ist
$stmt = $conn->prepare("SELECT filename FROM files WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $fileId, $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($file = $result->fetch_assoc()) {
    $filename = htmlspecialchars($file['filename']);

    // Berechtigungen ändern/entziehen
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_user_id'])) {
            // Berechtigung löschen
            $delUserId = (int)$_POST['delete_user_id'];
            $stmt = $conn->prepare("DELETE FROM file_shares WHERE file_id = ? AND shared_with_user_id = ?");
            $stmt->bind_param("ii", $fileId, $delUserId);
            $stmt->execute();
            echo "<p style='color:green;'>✅ Berechtigung entfernt.</p>";
        } elseif (isset($_POST['update_user_id'], $_POST['permission'])) {
            // Berechtigung ändern
            $updUserId = (int)$_POST['update_user_id'];
            $newPermission = $_POST['permission'] === 'write' ? 'write' : 'read';
            $stmt = $conn->prepare("UPDATE file_shares SET permission = ? WHERE file_id = ? AND shared_with_user_id = ?");
            $stmt->bind_param("sii", $newPermission, $fileId, $updUserId);
            $stmt->execute();
            echo "<p style='color:green;'>✅ Berechtigung aktualisiert.</p>";
        }
    }

    // Liste aller geteilten User
    $stmt = $conn->prepare("
        SELECT fs.shared_with_user_id, fs.permission, u.email
        FROM file_shares fs
        JOIN users u ON fs.shared_with_user_id = u.id
        WHERE fs.file_id = ?
    ");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $shares = $stmt->get_result();
} else {
    die("❌ Keine Berechtigung, da Sie nicht der Besitzer sind.");
}
?>

<h2>Berechtigungen verwalten für: <?= $filename ?></h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>E-Mail</th>
        <th>Berechtigung</th>
        <th>Aktionen</th>
    </tr>
    <?php while ($row = $shares->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="update_user_id" value="<?= $row['shared_with_user_id'] ?>">
                    <select name="permission" onchange="this.form.submit()">
                        <option value="read" <?= $row['permission'] === 'read' ? 'selected' : '' ?>>Lesen</option>
                        <option value="write" <?= $row['permission'] === 'write' ? 'selected' : '' ?>>Bearbeiten</option>
                    </select>
                </form>
            </td>
            <td>
                <form method="post" style="display:inline;" onsubmit="return confirm('❌ Berechtigung wirklich entfernen?');">
                    <input type="hidden" name="delete_user_id" value="<?= $row['shared_with_user_id'] ?>">
                    <button type="submit">Entziehen</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<p><a href="files.php">⬅ Zurück zur Übersicht</a></p>
