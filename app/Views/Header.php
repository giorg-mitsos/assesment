<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<header class="header">
    <div class="header-container">
        <div class="header-logo">
            <img src="/assets/logo.png" alt="Logo" class="logo">
        </div>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error-alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <div class="logout-button-container">
            <a href="/logout" class="logout-button">Logout</a>
        </div>
    </div>
</header>