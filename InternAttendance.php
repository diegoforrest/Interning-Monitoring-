<?php
session_start();

$serverName = "localhost";  // Change this if needed
$username = "root";         // Default for XAMPP
$password = "";             // Default for XAMPP
$database = "internship_monitoring"; // Make sure this is the correct database name

// Connect to MySQL
$conn = new mysqli($serverName, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id']; // Assuming student_id is stored in session
    $date = $_POST['date'];
    $status = $_POST['status'];
    $time_in = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? null;
    $rendered_hours = 0;

    if ($status === "present" && !empty($time_in) && !empty($time_out)) {
        $time_in_dt = new DateTime($time_in);
        $time_out_dt = new DateTime($time_out);
        $interval = $time_in_dt->diff($time_out_dt);
        $rendered_hours = $interval->h + ($interval->i / 60); // Convert to decimal format
    }

    // Use MySQLi Prepared Statements
    $query = "INSERT INTO intern_attendance (student_id, date, status, time_in, time_out, rendered_hours) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("issssd", $student_id, $date, $status, $time_in, $time_out, $rendered_hours);
        if ($stmt->execute()) {
            header("Location: InternReport.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error in preparing statement: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/InternAttend.css">
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
                <a href="InternAttendance.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
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
                <a href="InternReport.php" style="text-decoration: none;">Intern Reports</a>  <!-- Change report.php or report2.php -->
            </li>
            <div class="btn">
            <a href="logout.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Log Out </a>
         </div>
        </ul>
    </nav>


<div class="attendance-container">
    <h2 style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Attendance</h2>
    <form method="POST">
        <label for="status">Select Status</label>
        <select id="status" name="status" required>
            <option value="">(Select an option)</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
        </select>

        <label for="date">Select Date</label>
        <input type="date" id="date" name="date" required>

        <label for="time_in">Time In</label>
        <input type="time" id="time_in" name="time_in" onchange="enableTimeOut()" >

        <label for="time_out">Time Out</label>
        <input type="time" id="time_out" name="time_out" onchange="enableTimeOut()" >

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>
