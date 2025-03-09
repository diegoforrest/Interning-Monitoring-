<?php
session_start();

// Database connection
$serverName = "localhost";  
$dbUsername = "root";  
$dbPassword = "";  
$dbName = "internship_monitoring";  

$conn = new mysqli($serverName, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all interns with their assigned company and completed hours
$sql = "SELECT s.student_id, s.intern_name, s.company_name, 
               s.hours_required, 
               COALESCE(SUM(a.rendered_hours), 0) AS completed_hours
        FROM studentlogin s
        LEFT JOIN intern_attendance a ON s.student_id = a.student_id
        GROUP BY s.student_id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/AdminReports.css">
    <title>Internship Status Report</title>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <!-- Display Logged-in Admin -->
        <div class="company-name">
            Logged in as: Admin
        </div>

        <!-- Logout Button -->
        <div class="logout-container">
            <a href="logout.php">Log Out</a>
        </div>

        <!-- Logo -->
        <div class="logo-container">
            <img src="image/favicon.png" alt="logo" width="50" />
            <div class="logo">Internship Monitoring System</div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="AdminDashboard.php">Dashboard</a>
        <a href="AdminReport.php">Reports</a>
    </div>

    <!-- Internship Status Report -->
    <div class="report-container">
        <h2>Internship Status Report</h2>
        <table class="report-table">
            <tr>
                <th>Intern Name</th>
                <th>Assigned Company</th>
                <th>Completed Hours</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['intern_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                    <td><?php echo $row['completed_hours'] . " / " . $row['hours_required']; ?></td>
                    <td>
                        <?php 
                            if ($row['completed_hours'] >= $row['hours_required']) {
                                echo "Completed";
                            } elseif ($row['completed_hours'] > 0) {
                                echo "Ongoing";
                            } else {
                                echo "Pending";
                            }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>

<?php
$conn->close();
?>
