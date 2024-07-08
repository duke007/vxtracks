<?php
// register.php
require 'db.php';

$registrationMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = "USER";
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, status) VALUES (?, ?, ?, ?)");
    
    // Check if the prepare() method was successful
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $status);

        if ($stmt->execute()) {
            $registrationMessage = "Registration successful!";
        } else {
            $registrationMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $registrationMessage = "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Optional: Add custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Register</h2>
        <?php if ($registrationMessage != '') { echo "<div class='alert alert-info'>$registrationMessage</div>"; } ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <!-- Bootstrap JS (Optional) - for Bootstrap components that require JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
