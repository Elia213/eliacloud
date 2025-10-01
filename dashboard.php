<?php
include 'includes/loged_in_check.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f8ff;
            color: #222;
        }
        .content {
            min-height: 90vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        h2 {
            color: #4b83ec;
            margin-bottom: 0.7rem;
        }
        .features-row {
            display: flex;
            gap: 2rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .feature-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(75,131,236,0.08);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            min-width: 220px;
            max-width: 260px;
            text-align: center;
            transition: transform 0.15s;
        }
        .feature-card:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 6px 24px rgba(75,131,236,0.13);
        }
        .feature-icon {
            font-size: 2.2rem;
            color: #4b83ec;
            margin-bottom: 1rem;
        }
        .feature-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #3a5fa0;
            margin-bottom: 0.5rem;
        }
        .feature-desc {
            color: #5a6b8a;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <div class="content">
        <h2>Willkommen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>Du bist erfolgreich eingeloggt 🎉</p>
        <div class="features-row">
            <div class="feature-card">
                <div class="feature-icon">☁️</div>
                <div class="feature-title">Cloudspeicher</div>
                <div class="feature-desc">Das Speichern in der Eliacloud gibt ihen von mehreren Geräte zugriff auf ihre Dokumente</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <div class="feature-title">Sicherheit</div>
                <div class="feature-desc">Das Abspeichern von Dokumenten in der Eliacloud kann benutzt werden um Dokummente als Backup zu sichern .</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🤝</div>
                <div class="feature-title">Einfache Freigabe</div>
                <div class="feature-desc">Teilen Sie Dateien mit Freunden oder Kollegen schnell und einfach.</div>
            </div>
        </div>
    </div>
</body>
</html>
