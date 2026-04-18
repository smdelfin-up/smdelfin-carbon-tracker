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