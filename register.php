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
</head>
<body>
    <h2>Register</h2>
    <?php if ($registrationMessage != '') { echo "<p>$registrationMessage</p>"; } ?>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Login form</a>
</body>
</html>
