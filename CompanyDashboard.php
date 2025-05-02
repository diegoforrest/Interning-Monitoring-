<?php
session_start();
if (!isset($_SESSION['company_email'])) {
    header("Location: CompanyLogin.php"); // Redirect to CompanyLogin.php
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

// Fetch company details
$sql_company = "SELECT * FROM company WHERE email = ?";
$stmt = $conn->prepare($sql_company);
$stmt->bind_param("s", $company_email);
$stmt->execute();
$result_company = $stmt->get_result();

if ($result_company->num_rows > 0) {
    $company = $result_company->fetch_assoc();
    $company_name = $company['company_name']; // Get company name
} else {
    session_destroy();
    header("Location: CompanyLogin.php?error=CompanyNotFound");
    exit();
}

// Count number of interns assigned to this company
$sql_count = "SELECT COUNT(*) as total_interns FROM studentlogin WHERE company_name = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("s", $company_name);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_interns = $row_count['total_interns']; // Store the total interns count

// Get today's date
$today = date('Y-m-d');

// Count interns who checked in today for the logged-in company
$sql_present_interns = "
    SELECT COUNT(DISTINCT student_id) AS present_interns 
    FROM intern_attendance 
    WHERE date = ? 
    AND student_id IN (
        SELECT student_id FROM studentlogin WHERE company_name = ?
    )
";

$stmt_present = $conn->prepare($sql_present_interns);
$stmt_present->bind_param("ss", $today, $company_name); 
$stmt_present->execute();
$result_present = $stmt_present->get_result();
$row_present = $result_present->fetch_assoc();
$present_interns = $row_present['present_interns'] ?? 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/CompanyDash.css">
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
                <a href="CompanyDashboard.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Monitoring System
                    </span>
                </a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="CompanyInterns.php" style="text-decoration: none;">Company Interns</a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="CompanyReport.php" style="text-decoration: none;">Company Reports</a>  <!-- Change report.php or report2.php -->
            </li>

            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>
    
    <div class="company-name">
            Logged in as: <?php echo htmlspecialchars($company_name); ?>
            </div>

    <!-- Rendered Hours & Remaining Hours Section -->
    <div class="container">
        <div class="box1">
            No. of Interns
            <div class="value"><?php echo $total_interns; ?></div>
        </div>
        <div class="box2">
            Interns Present
            <div class="value"><?php echo $present_interns; ?></div>
        </div>
        <div class="box3">
            Pending Interns
            <div class="value">3</div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
