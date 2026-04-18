<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 2px solid var(--light-green); padding-bottom: 10px;">
    <h2 style="margin: 0;">Welcome, <span style="color: var(--accent-green);"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h2>
    <a href="logout.php" style="color: #d90429; font-size: 0.9rem;">Logout</a>
</div>

<div class="dashboard-grid" style="display: flex; flex-direction: column; gap: 20px;">
    
    <div style="background: var(--primary-green); color: white; padding: 20px; border-radius: 12px; text-align: center;">
        <p style="margin: 0; font-size: 0.9rem; opacity: 0.9;">Your Total Footprint</p>
        <h1 style="margin: 5px 0; font-size: 2.5rem;">0.00 <small style="font-size: 1rem;">kg CO2</small></h1>
    </div>

    <div class="container" style="max-width: 100%; margin: 0; text-align: left;">
        <h3>Log New Activity</h3>
        <form method="POST" action="">
            <label>Activity Type:</label>
            <select name="activity_type">
                <option value="travel">Travel (km)</option>
                <option value="electricity">Electricity (kWh)</option>
            </select>
            
            <label>Amount:</label>
            <input type="number" name="amount" step="0.01" placeholder="e.g. 15.5" required>
            
            <button type="submit" name="log_activity">Log Activity</button>
        </form>
    </div>

    <div style="background: #e9f5ee; border-left: 5px solid var(--accent-green); padding: 15px; border-radius: 4px;">
        <h4 style="margin-top: 0; color: var(--primary-green);">🌱 Eco-Friendly Recommendation</h4>
        <p style="margin-bottom: 0; font-size: 0.95rem;">Great job! Keep using energy-efficient appliances to further reduce your impact.</p>
    </div>
</div>