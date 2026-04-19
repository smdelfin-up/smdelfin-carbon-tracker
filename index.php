<?php
include 'db.php';

$db_status  = false;
$db_message = "";

try {
    $stmt = $pdo->query("SELECT 1");
    if ($stmt) {
        $db_status  = true;
        $db_message = "Systems Operational";
    }
} catch (PDOException $e) {
    $db_status  = false;
    $db_message = "Connection Issue";
    // error_log($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack 2026 | Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container home-container">

        <div class="brand-logo">🌿</div>
        <h1 class="home-title">EcoTrack 2026</h1>
        <p class="subtitle">Your personal companion for monitoring and reducing your carbon footprint.</p>

        <!-- DB Status Badge -->
        <div class="status-badge-wrap">
            <?php if ($db_status): ?>
                <span class="status-badge status-ok">● <?php echo htmlspecialchars($db_message); ?></span>
            <?php else: ?>
                <span class="status-badge status-err">○ <?php echo htmlspecialchars($db_message); ?></span>
            <?php endif; ?>
        </div>

        <!-- Project Goals -->
        <div class="home-goals">
            <h3>Project Goals</h3>
            <ul class="goals-list">
                <li>🌍 <strong>Track</strong> daily travel and energy usage.</li>
                <li>📊 <strong>Analyze</strong> footprint data via Azure MySQL.</li>
                <li>🌱 <strong>Improve</strong> sustainability for CMSC 207.</li>
            </ul>
        </div>

        <!-- CTA Buttons -->
        <div class="home-actions">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-accent">Register</a>
        </div>

        <p class="home-footer">Deployed on Azure App Service &amp; MySQL Flexible Server</p>
    </div>
</body>
</html>
