<?php
include 'db.php'; // Ensure this matches your filename (e.g., db.php)

$db_status = false;
$db_message = "";

try {
    // Attempt a simple query to verify connection and table existence
    $stmt = $pdo->query("SELECT 1");
    if ($stmt) {
        $db_status = true;
        $db_message = "Systems Operational";
    }
} catch (PDOException $e) {
    $db_status = false;
    $db_message = "Connection Issue: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carbon Tracker | Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1 style="color: var(--primary-green);">EcoTrack 2026</h1>
        
        <div style="margin-bottom: 20px;">
            <?php if ($db_status): ?>
                <span style="background: #d8f3dc; color: #2d6a4f; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #2d6a4f;">
                    ● <?php echo $db_message; ?>
                </span>
            <?php else: ?>
                <span style="background: #ffe5ec; color: #d90429; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; border: 1px solid #d90429;">
                    ○ <?php echo $db_message; ?>
                </span>
            <?php endif; ?>
        </div>

        <p>Your personal companion for monitoring and reducing your carbon footprint.</p>
        
        <div style="background: var(--light-green); padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
            <h3>Project Goals</h3>
            <ul style="list-style: none; padding: 0;">
                <li>🌍 <strong>Track</strong> daily travel and energy usage.</li>
                <li>📊 <strong>Analyze</strong> footprint data via Azure MySQL.</li>
                <li>🌱 <strong>Improve</strong> sustainability for CMSC 207.</li>
            </ul>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <a href="login.php" style="background: var(--primary-green); color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none;">Login</a>
            <a href="register.php" style="background: var(--accent-green); color: white; padding: 12px 25px; border-radius: 6px; text-decoration: none;">Register</a>
        </div>

        <p style="margin-top: 20px; font-size: 0.8rem; color: #666;">
            Deployment Environment: Azure App Service & MySQL Flexible Server
        </p>
    </div>
</body>
</html>