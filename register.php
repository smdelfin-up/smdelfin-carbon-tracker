<?php
include 'db_connect.php'; // Includes your PDO connection with the SSL certificate

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // 1. Hash the password (Security best practice)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // 2. Insert into the database
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $hashed_password]);

            $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
            header("Location: /login.php?success=registered");
            exit(); // Always use exit() after a header redirect!
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Check for duplicate username
                $error = "Username already exists. Please choose another.";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Carbon Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
        
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>
            <br>
            <label>Password:</label>
            <input type="password" name="password" required>
            <br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>