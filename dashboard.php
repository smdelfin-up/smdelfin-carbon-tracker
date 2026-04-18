<?php
session_start();
include 'db.php'; // Ensure this matches your connection filename

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_emissions = 0.00;

// Handle New Activity Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['log_activity'])) {
    $type = $_POST['activity_type'];
    $amount = floatval($_POST['amount']);
    
    // Emissions factors: 0.21 kg per km for travel, 0.45 kg per kWh for electricity
    $factor = ($type == 'travel') ? 0.21 : 0.45; 
    $emissions = $amount * $factor;

    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, activity_type, amount, emissions, date_logged) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $type, $amount, $emissions]);
}

// Fetch Total Footprint for the User
$stmt = $pdo->prepare("SELECT SUM(emissions) as total FROM activity_logs WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
if ($row['total']) {
    $total_emissions = $row['total'];
}
?>
<div style="background: var(--primary-green); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px;">
    <p style="margin: 0; opacity: 0.9;">Your Total Footprint</p>
    <h1 style="margin: 5px 0; font-size: 2.5rem;">
        <?php echo number_format($total_emissions, 2); ?> <small style="font-size: 1rem;">kg CO2</small>
    </h1>
</div>
<div class="container" style="max-width: 800px; margin-top: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--light-green); padding-bottom: 10px; margin-bottom: 20px;">
        <h2 style="margin: 0;">Welcome, <span style="color: var(--primary-green);"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h2>
        <a href="logout.php" style="color: #d90429; font-weight: bold;">Logout</a>
    </div>

    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
        <h3>Log New Activity</h3>
        <form method="POST" action="dashboard.php" style="display: flex; flex-direction: column; gap: 10px;">
            <div>
                <label>Activity Type:</label>
                <select name="activity_type" style="width: 100%;">
                    <option value="travel">Travel (km)</option>
                    <option value="electricity">Electricity (kWh)</option>
                </select>
            </div>
            <div>
                <label>Amount:</label>
                <input type="number" name="amount" step="0.01" placeholder="e.g. 15.5" required>
            </div>
            <button type="submit" name="log_activity">Log Activity</button>
        </form>
    </div>

    <div style="margin-top: 30px; background-color: var(--light-green); padding: 15px; border-radius: 8px; border-left: 5px solid var(--primary-green);">
        <p style="margin: 0;">🌱 <strong>Eco-Friendly Recommendation:</strong> Great job! Keep using energy-efficient appliances to further reduce your impact.</p>
    </div>
</div>