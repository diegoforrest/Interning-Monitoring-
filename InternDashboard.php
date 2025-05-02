<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: StudentLogin.php");
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

// Get student ID from session
$student_id = $_SESSION['student_id'];

// Fetch student details
$sql_student = "SELECT * FROM studentlogin WHERE student_id = '$student_id'";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $student = $result_student->fetch_assoc();
    $hours_required = $student['hours_required'];
} else {
    die("Student not found.");
}

// Fetch total rendered hours from attendance records
$sql_rendered_hours = "SELECT SUM(rendered_hours) AS total_rendered FROM intern_attendance WHERE student_id = '$student_id'";
$result_rendered_hours = $conn->query($sql_rendered_hours);
$row = $result_rendered_hours->fetch_assoc();
$renderedHours = $row['total_rendered'] ?? 0;

// Calculate remaining hours
$remainingHours = $hours_required - $renderedHours;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/InternDash.css">
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
                <a href="InternDashboard.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Management
                    </span>
                </a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="InternAttendance.php" style="text-decoration: none;">Intern Attendance</a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="InternReport.php" style="text-decoration: none;">Intern Reports</a>  <!-- Change report.php or report2.php -->
            </li>
            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>

    <!-- Rendered Hours & Remaining Hours Section -->
    <div class="container">
        <div class="box1" style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">
            Rendered Hours
            <div class="value" ><?php echo $renderedHours; ?></div>
        </div>
        <div class="box2" style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">
            Remaining Hours
            <div class="value"><?php echo $remainingHours; ?></div>
        </div>
    </div>

    <div class="gauge-container">
    <canvas id="gaugeChart"></canvas>
    <div class="gauge-text">
        <?php echo round(($renderedHours / $hours_required) * 100, 2); ?>% Completed
    </div>
    </div>

    <script>
    const ctx = document.getElementById('gaugeChart').getContext('2d');
    const renderedHours = <?php echo $renderedHours; ?>;
    const requiredHours = <?php echo $hours_required; ?>;
    const percentage = (renderedHours / requiredHours) * 100;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [renderedHours, requiredHours - renderedHours], 
                backgroundColor: [
                    'rgba(14, 121, 17, 1)',   // Green for completed hours
                    'rgb(200,200,200, 1)'  // Light gray for remaining hours
                ],
                hoverBackgroundColor: [
                    'rgba(23, 185, 28, 1)',   // Slightly brighter green on hover
                    'rgb(150, 148, 148)'  
                ],
                borderWidth: 0,
                borderRadius: 5
            }]
        },
        options: {
            rotation: -90,         // Start from top
            circumference: 180,     // Half-circle
            cutout: '70%',         // Inner cutout for gauge effect
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: true, // Smooth animation
                duration: 1500
            },
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
</script>


</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
