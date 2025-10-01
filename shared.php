<?php
require __DIR__ . '/includes/db.php';
include 'includes/loged_in_check.php';

// ggf. Filter/Logik hier oben
$userId = $_SESSION['user_id'];

// Alle Dateien, die MIT MIR geteilt sind
$stmt = $conn->prepare("
    SELECT f.id, f.filename, f.uploaded_at, u.email AS owner_email, fs.permission
    FROM file_shares fs
    JOIN files f ON f.id = fs.file_id
    JOIN users u ON u.id = f.user_id
    WHERE fs.shared_with_user_id = ?
    ORDER BY f.uploaded_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$shared = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Geteilte Dateien</title>
</head>
<body>

  <?php include 'includes/navbar.php'; ?>
<div class="content">

  <h2>Mit dir geteilte Dateien</h2>

  <?php if ($shared->num_rows === 0): ?>
    <p>Es wurden noch keine Dateien mit dir geteilt.</p>
  <?php else: ?>
    <table border="1" cellpadding="6" cellspacing="0">
      <tr>
        <th>Dateiname</th>
        <th>Besitzer</th>
        <th>Rechte</th>
        <th>Hochgeladen am</th>
        <th>Aktionen</th>
      </tr>
      <?php while ($row = $shared->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['filename']) ?></td>
          <td><?= htmlspecialchars($row['owner_email']) ?></td>
          <td><?= htmlspecialchars($row['permission']) ?></td>
          <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
          <td>
            <a href="download.php?id=<?= (int)$row['id'] ?>">Herunterladen</a>
            |
            <a href="edit.php?id=<?= (int)$row['id'] ?>">Öffnen</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php endif; ?>

  <p><a href="dashboard.php">⬅ Zurück zum Dashboard</a></p>

</div>
</body>
</html>
