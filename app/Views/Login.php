<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body class="background">

    <div class="logo-container">
        <img src="/assets/logo.png" alt="Logo" class="logo">
    </div>
    <div class="login-container">
        <h1>Login</h1>

        <form action="/login" method="POST">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <?php if (isset($_SESSION['error'])): ?>
                    <p class="error-message"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>

</body>

</html>
