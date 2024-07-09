<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'ADMIN') {
    header("Location: login.php");
    exit();
}

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'reject') {
        $status = 'Rejected';
    } else {
        $status = 'Pending';
    }

    $stmt = $conn->prepare("UPDATE vehicle_requests SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $id);
        if ($stmt->execute()) {
            $message = "Request $status successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }

    $conn->close();
    header("Location: admin.php?message=" . urlencode($message));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Request</title>
</head>
<body>
    <h1>Process Request</h1>
    <p><?php echo $message; ?></p>
    <a href="admin.php">Back to Admin Page</a>
</body>
</html>
