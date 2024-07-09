<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $description = $_POST['description'];
    $tag = $_POST['tag'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO vehicle_requests (user_id, vehicle_type, model, year, description, tags) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Check if the prepare() method was successful
    if ($stmt) {
        $stmt->bind_param("isssss", $user_id, $vehicle_type, $model, $year, $description, $tag);

        if ($stmt->execute()) {
            $message = "Vehicle request submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }

    $conn->close();
    header("Location: user.php?message=" . urlencode($message));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Request</title>
</head>
<body>
    <h1>Vehicle Request</h1>
    <p><?php echo $message; ?></p>
    <a href="user.php">Back to User Page</a>
</body>
</html>
