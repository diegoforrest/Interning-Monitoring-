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

// Fetch total interns and total companies
$sql = "SELECT 
            (SELECT COUNT(*) FROM studentlogin) AS total_interns, 
            (SELECT COUNT(*) FROM company) AS total_companies";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalInterns = $row['total_interns'];
    $totalCompanies = $row['total_companies'];
} else {
    $totalInterns = 0;
    $totalCompanies = 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/AdminDashboard.css">
    <title>Dashboard</title>
</head>
<body>
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
        <a href="AdminReports.php">Reports</a>
    </div>

    <!-- Rendered Hours & Remaining Hours Section -->
    <div class="container">
        <div class="box1">
        <p>Total Interns: <?php echo $totalInterns; ?></p>
        </div>
        <div class="box2">
        <p>Total Registered Companies: <?php echo $totalCompanies; ?></p>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

