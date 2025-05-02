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
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>


<nav>
        <ul style="list-style-type: none; padding: 0;">
            <li style="display: flex; align-items: center; margin-top: 10px;">
                <a href="admindashboard.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Management
                    </span>
                </a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="AdminReports.php" style="text-decoration: none;">Reports</a>
            </li>
            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>

    <!-- Rendered Hours & Remaining Hours Section -->
    <div class="container">
        <div class="card">
        <p>Total Interns: <?php echo $totalInterns; ?></p>
        </div>

        <div class="card2">
        <p>Total Registered Companies: <?php echo $totalCompanies; ?></p>
        </div>
    </div>



 

</body>
</html>





<?php
// Close the database connection
$conn->close();
?>

