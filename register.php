<?php
include 'db.php';

// Redirect if already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $error = "Username must be between 3 and 30 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Username may only contain letters, numbers, and underscores.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            // ✅ FIXED: Simple relative redirect
            header("Location: login.php?registered=1");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Username already taken. Please choose another.";
            } else {
                $error = "A database error occurred. Please try again.";
                // error_log($e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – EcoTrack 2026</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="brand-logo">🌿</div>
        <h2>Create Account</h2>
        <p class="subtitle">Start tracking your carbon footprint today.</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username"
                   value="<?php echo htmlspecialchars($username ?? ''); ?>"
                   minlength="3" maxlength="30" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="new-password"
                   minlength="8" required>
            <small class="field-hint">At least 8 characters.</small>

            <button type="submit">Create Account</button>
        </form>

        <p class="form-footer">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
