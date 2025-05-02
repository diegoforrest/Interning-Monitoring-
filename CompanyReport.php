<?php
session_start();
if (!isset($_SESSION['company_email'])) {
    header("Location: CompanyLogin.php");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "internship_monitoring";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get company email from session
$company_email = $_SESSION['company_email'];

// Fetch company name
$sql_company = "SELECT company_name FROM company WHERE email = ?";
$stmt = $conn->prepare($sql_company);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result_company = $stmt->get_result();

if ($result_company->num_rows > 0) {
    $company = $result_company->fetch_assoc();
    $company_name = $company['company_name'];
} else {
    session_destroy();
    header("Location: CompanyLogin.php?error=CompanyNotFound");
    exit();
}

// Fetch interns assigned to this company
$sql_interns = "SELECT student_id, intern_name FROM studentlogin WHERE company_name = ?";
$stmt = $conn->prepare($sql_interns);
$stmt->bind_param("s", $company_name);
$stmt->execute();
$result_interns = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet"href="css/CompanyRep.css">
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Internship Monitoring System</title>

    </head>
<body>

<nav>
        <ul style="list-style-type: none; padding: 0;">
            <li style="display: flex; align-items: center; margin-top: 10px;">
                <a href="CompanyReport.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Monitoring System
                    </span>
                </a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="CompanyDashboard.php" style="text-decoration: none;">Company Dashboard</a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="CompanyInterns.php" style="text-decoration: none;">Company Interns</a>  <!-- Change report.php or report2.php -->
            </li>

            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>
    
    <div class="company-name">
            Logged in as: <?php echo htmlspecialchars($company_name); ?>
            </div>




    <div class="report-container">
    <h2 style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Summary Report</h2>
    <table class="report-table">
        <tr>
            <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Student ID</th>
            <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Intern Name</th>
            <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Download</th>
        </tr>
        <?php while ($row = $result_interns->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                <td><?php echo htmlspecialchars($row['intern_name']); ?></td>
                <td><a href="DownloadReport.php?student_id=<?php echo $row['student_id']; ?>">Download</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>


    </body>
</html>