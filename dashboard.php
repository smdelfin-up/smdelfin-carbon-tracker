<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_emissions = 0.00;
$log_error = "";

// Handle new activity log submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['log_activity'])) {
    $type   = $_POST['activity_type'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);

    $allowed_types = ['travel', 'electricity'];
    if (!in_array($type, $allowed_types) || $amount <= 0) {
        $log_error = "Invalid activity data. Please enter a positive amount.";
    } else {
        // Emission factors: travel = 0.21 kg CO2/km, electricity = 0.45 kg CO2/kWh
        $factors = ['travel' => 0.21, 'electricity' => 0.45];
        $emissions = $amount * $factors[$type];

        $stmt = $pdo->prepare(
            "INSERT INTO activity_logs (user_id, activity_type, amount, emissions, date_logged)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->execute([$user_id, $type, $amount, $emissions]);

        // PRG pattern: redirect to prevent duplicate submissions on refresh
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'];
        $dir    = rtrim(dirname($_SERVER['PHP_SELF']), '/');
        header("Location: {$scheme}://{$host}{$dir}/dashboard.php?logged=1");
        exit();
    }
}

// Fetch total emissions
$stmt = $pdo->prepare("SELECT SUM(emissions) AS total FROM activity_logs WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$total_emissions = $row['total'] ?? 0.00;

// Fetch recent activity logs (last 10)
$stmt = $pdo->prepare(
    "SELECT activity_type, amount, emissions, date_logged
     FROM activity_logs
     WHERE user_id = ?
     ORDER BY date_logged DESC
     LIMIT 10"
);
$stmt->execute([$user_id]);
$recent_logs = $stmt->fetchAll();

// Simple eco tip based on total emissions
$eco_tip = "Great start! Keep logging to understand your footprint.";
if ($total_emissions > 100) {
    $eco_tip = "Consider carpooling or using public transport to cut travel emissions.";
} elseif ($total_emissions > 50) {
    $eco_tip = "Try switching to energy-efficient appliances to reduce electricity emissions.";
} elseif ($total_emissions > 10) {
    $eco_tip = "You're doing well! Short walks instead of drives make a big difference.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – EcoTrack 2026</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <div class="container dashboard-container">

        <!-- Header -->
        <div class="dashboard-header">
            <div>
                <span class="brand-inline">🌿 EcoTrack</span>
                <span class="welcome-text">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            </div>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>

        <!-- Total Emissions Card -->
        <div class="emissions-card">
            <p class="emissions-label">Your Total Carbon Footprint</p>
            <div class="emissions-value">
                <?php echo number_format($total_emissions, 2); ?>
                <span class="emissions-unit">kg CO₂</span>
            </div>
            <?php
                $level = "Low";
                $level_class = "level-low";
                if ($total_emissions > 100) { $level = "High"; $level_class = "level-high"; }
                elseif ($total_emissions > 30) { $level = "Moderate"; $level_class = "level-moderate"; }
            ?>
            <span class="emissions-level <?php echo $level_class; ?>"><?php echo $level; ?> Impact</span>
        </div>

        <?php if (isset($_GET['logged'])): ?>
            <div class="alert alert-success">✅ Activity logged successfully!</div>
        <?php endif; ?>

        <?php if ($log_error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($log_error); ?></div>
        <?php endif; ?>

        <!-- Log Activity Form -->
        <div class="card">
            <h3>Log New Activity</h3>
            <form method="POST" action="dashboard.php" class="log-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="activity_type">Activity Type</label>
                        <select id="activity_type" name="activity_type">
                            <option value="travel">🚗 Travel (km)</option>
                            <option value="electricity">⚡ Electricity (kWh)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount"
                               step="0.01" min="0.01" placeholder="e.g. 15.5" required>
                    </div>
                    <div class="form-group form-group-btn">
                        <label>&nbsp;</label>
                        <button type="submit" name="log_activity">Log Activity</button>
                    </div>
                </div>
            </form>
            <small class="field-hint">Emission factors: Travel = 0.21 kg CO₂/km &nbsp;|&nbsp; Electricity = 0.45 kg CO₂/kWh</small>
        </div>

        <!-- Eco Tip -->
        <div class="eco-tip">
            🌱 <strong>Eco Tip:</strong> <?php echo htmlspecialchars($eco_tip); ?>
        </div>

        <!-- Recent Activity Log -->
        <?php if (!empty($recent_logs)): ?>
        <div class="card">
            <h3>Recent Activity</h3>
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Emissions</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_logs as $log): ?>
                    <tr>
                        <td>
                            <?php echo $log['activity_type'] === 'travel' ? '🚗 Travel' : '⚡ Electricity'; ?>
                        </td>
                        <td>
                            <?php echo number_format($log['amount'], 2); ?>
                            <?php echo $log['activity_type'] === 'travel' ? 'km' : 'kWh'; ?>
                        </td>
                        <td><?php echo number_format($log['emissions'], 3); ?> kg CO₂</td>
                        <td><?php echo date('M j, Y g:i A', strtotime($log['date_logged'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="card empty-state">
            <p>No activities logged yet. Log your first activity above! 🌍</p>
        </div>
        <?php endif; ?>

    </div>
</body>
</html>
