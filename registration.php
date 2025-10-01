<!DOCTYPE html>
<html>
<head>
    <title>Registrieren</title>
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
        .register-box {
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 16px rgba(75,131,236,0.08);
            min-width: 320px;
        }
        h2 {
            color: #4b83ec;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            color: #3a5fa0;
            font-weight: 500;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.7rem;
            margin-top: 0.3rem;
            margin-bottom: 1.2rem;
            border: 1.5px solid #c7d6f7;
            border-radius: 5px;
            font-size: 1rem;
            background: #f4f8ff;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid #4b83ec;
            outline: none;
        }
        button[type="submit"] {
            width: 100%;
            padding: 0.8rem;
            background: #4b83ec;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background: #3766b1;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #5a6b8a;
            font-size: 1rem;
        }
        .login-link a {
            color: #4b83ec;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-box">
            <h2>Registrieren</h2>
            <form method="POST" action="registration_process.php">
                <label>Benutzername:</label><br>
                <input type="text" name="username" required><br>

                <label>E-Mail:</label><br>
                <input type="email" name="email" required><br>

                <label>Passwort:</label><br>
                <input type="password" name="password" required><br>

                <label>Passwort wiederholen:</label><br>
                <input type="password" name="confirm_password" required><br>

                <button type="submit">Registrieren</button>
            </form>
            <div class="login-link">
                Schon ein Konto? <a href="login.php">Hier einloggen</a>
            </div>
        </div>
    </div>
</body>
</html>
