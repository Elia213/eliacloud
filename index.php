<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliacloud - Ihre Cloudlösung</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f8ff;
            color: #222;
        }
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .title {
            font-size: 3rem;
            font-weight: bold;
            color: #4b83ec;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 1.3rem;
            color: #3a5fa0;
            margin-bottom: 2.5rem;
        }
        .button-group {
            display: flex;
            gap: 1.5rem;
        }
        .btn {
            padding: 0.8rem 2.2rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #4b83ec;
            color: #fff;
            font-weight: 500;
            transition: background 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(75,131,236,0.08);
        }
        .btn:hover {
            background: #3766b1;
        }
        .info {
            margin-top: 2.5rem;
            color: #5a6b8a;
            font-size: 1rem;
            max-width: 420px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Eliacloud</div>
        <div class="subtitle">Ihre persönliche, sichere und einfache Cloudlösung</div>
        <div class="button-group">
            <a href="login.php" class="btn">Login</a>
            <a href="registration.php" class="btn" style="background: #fff; color: #4b83ec; border: 1.5px solid #4b83ec;">Registrieren</a>
        </div>
        <div class="info">
            <p>Speichern, teilen und verwalten Sie Ihre Dateien sicher in der Cloud.</p>
            
        </div>
    </div>
</body>
</html>