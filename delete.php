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

// Datei prüfen
$stmt = $conn->prepare("SELECT filepath FROM files WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $fileId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filePath = $row['filepath'];

    // Datei auf Server löschen
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Eintrag in DB löschen
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $fileId, $userId);
    $stmt->execute();

    header("Location: files.php?deleted=1");
    exit();
} else {
    die("❌ Keine Berechtigung");
}