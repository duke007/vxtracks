<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'ADMIN') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$sql = "SELECT vr.id, u.username, vr.vehicle_type, vr.model, vr.year, vr.description, vr.tags, vr.request_date, vr.status 
        FROM vehicle_requests vr 
        JOIN users u ON vr.user_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Optional custom styles */
        body {
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .btn-group {
            display: flex;
        }
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <h2 class="mt-4">Vehicle Requests</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Vehicle Type</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Description</th>
                        <th>Tags</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tags']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td class='btn-group'>
                                    <form action='process_request.php' method='post'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <button type='submit' class='btn btn-success btn-sm' name='action' value='approve'>Approve</button>
                                    </form>
                                    <form action='process_request.php' method='post'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                                        <button type='submit' class='btn btn-danger btn-sm' name='action' value='reject'>Reject</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>No vehicle requests found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Optional Bootstrap JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
