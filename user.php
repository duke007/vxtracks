<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'USER') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$user_id = $_SESSION['user_id'];

$sql = "SELECT vehicle_type, model, year, description, tags, request_date, status FROM vehicle_requests WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Optional custom styles */
        body {
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .form-control {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <?php if ($message) { ?>
            <div class="alert alert-success mt-3" role="alert">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h2 class="mt-4">Vehicle Request Form</h2>
        <form action="vehicle_request.php" method="post">
            <div class="form-group">
                <label for="vehicle_type">Vehicle Type:</label>
                <input type="text" id="vehicle_type" name="vehicle_type" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>

        <h2 class="mt-5">Your Vehicle Requests</h2>
        <table class="table table-striped table-bordered mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>Vehicle Type</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Description</th>
                    <th>Tags</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tags']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No vehicle requests found</td></tr>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Optional Bootstrap JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
