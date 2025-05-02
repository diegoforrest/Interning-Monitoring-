<?php
session_start(); // Ensure the session is started before accessing session variables

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Error: Student is not logged in.");
}
$host = "localhost"; // Change if needed
$user = "root"; // Change if needed
$password = ""; // Change if needed
$database = "internship_monitoring"; // Change this to your actual database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Student ID from session or GET parameter
$student_id = $_SESSION['student_id']; // Change this or get from session `$_SESSION['student_id']`

// Fetch student details
$sql_student = "SELECT * FROM studentlogin WHERE student_id = '$student_id'";
$result_student = $conn->query($sql_student);

if ($result_student->num_rows > 0) {
    $student = $result_student->fetch_assoc();
} else {
    die("Student not found.");
}

// Fetch attendance records

$sql_attendance = "SELECT * FROM intern_attendance WHERE student_id = '$student_id' ORDER BY date DESC";
$result_attendance = $conn->query($sql_attendance);


$attendance_records = [];
while ($row = $result_attendance->fetch_assoc()) {
    $attendance_records[] = $row;
}

// Calculate Total Rendered Hours & Remaining Hours
$total_rendered = array_sum(array_column($attendance_records, "rendered_hours"));
$remaining_hours = $student['hours_required'] - $total_rendered;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/InternRep.css">
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Student Internship Report</title>

</head>
<body>


<nav>
        <ul style="list-style-type: none; padding: 0;">
            <li style="display: flex; align-items: center; margin-top: 10px;">
                <a href="InternReport.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Management
                    </span>
                </a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="InternDashboard.php" style="text-decoration: none;">Intern Dashboard</a>
            </li>
            <li style="margin-top: 25px; margin-left: 20px; font-family: 'Robotolightnew', sans-serif; font-size: 18px;">
                <a href="InternAttendance.php" style="text-decoration: none;">Intern Attendance</a>  <!-- Change report.php or report2.php -->
            </li>
            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>

    <div class="report-container">
        <h2 style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Student Internship Report</h2>
        <p><nomral>Student ID:</nomral> <?php echo $student['student_id']; ?></p>
        <p><normal>Student Name:</normal> <?php echo $student['intern_name']; ?></p>

        <table class="report-table">
            <tr>
                <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Date</th>
                <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Status</th>
                <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Rendered Hours</th>
                <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Hours Required</th>
                <th style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Remaining Hours</th>
            </tr>
            <?php
            $current_total = 0;
            foreach ($attendance_records as $record) {
                $current_total += $record["rendered_hours"];
                $remaining = $student['hours_required'] - $current_total;
                echo "<tr>
                        <td>{$record['date']}</td>
                        <td><span class='status " . ($record['status'] == 'Present' ? 'status-present' : 'status-absent') . "'>{$record['status']}</span></td>
                        <td>{$record['rendered_hours']}</td>
                        <td>{$student['hours_required']}</td>
                        <td>{$remaining}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
