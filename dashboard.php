<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['activity_type'];
    $val = floatval($_POST['value']);
    
    // Simple math logic for the "Sustainability Impact"
    $factor = ($type == 'travel') ? 0.19 : 0.45;
    $emitted = $val * $factor;

    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, activity_type, value, carbon_emitted) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $val, $emitted]);
    $message = "Activity logged! You emitted " . number_format($emitted, 2) . " kg of CO2.";
}

// Fetch total for the user
$stmt = $pdo->prepare("SELECT SUM(carbon_emitted) as total FROM activity_logs WHERE user_id = ?");
$stmt->execute([$user_id]);
$total = $stmt->fetch()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carbon Tracker</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <p>Your Total Footprint: <strong><?php echo number_format($total, 2); ?> kg CO2</strong></p>

    <form method="POST">
        <select name="activity_type">
            <option value="travel">Travel (km)</option>
            <option value="electricity">Electricity (kWh)</option>
        </select>
        <input type="number" name="value" step="0.01" placeholder="Enter amount" required>
        <button type="submit">Log Activity</button>
    </form>

    <?php if ($message) echo "<p>$message</p>"; ?>

    <h3>Eco-Friendly Recommendations</h3>
    <ul>
        <?php if ($total > 50): ?>
            <li>Your footprint is high! Consider using a bicycle for short trips.</li>
        <?php else: ?>
            <li>Great job! Keep using energy-efficient appliances.</li>
        <?php endif; ?>
    </ul>
    
    <a href="logout.php">Logout</a>
</body>
</html>