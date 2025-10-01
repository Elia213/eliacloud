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

// 1. Gehört die Datei dem User?
$stmt = $conn->prepare("SELECT filename, filepath FROM files WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $fileId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filename = $row['filename'];
    $filepath = $row['filepath'];
} else {
    // 2. Wurde sie mit ihm geteilt?
    $stmt = $conn->prepare("
        SELECT f.filename, f.filepath 
        FROM file_shares fs
        JOIN files f ON fs.file_id = f.id
        WHERE fs.file_id = ? AND fs.shared_with_user_id = ?
    ");
    $stmt->bind_param("ii", $fileId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $filename = $row['filename'];
        $filepath = $row['filepath'];
    } else {
        die("❌ Keine Berechtigung für diese Datei.");
    }
}

// Datei senden
if (!file_exists($filepath)) {
    die("❌ Datei nicht gefunden");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Length: ' . filesize($filepath));
readfile($filepath);
exit;
?>