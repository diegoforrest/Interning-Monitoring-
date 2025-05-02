<?php
$serverName = "localhost"; 
$username = "root"; // Default XAMPP username
$password = ""; // Default is empty in XAMPP
$database = "internship_monitoring"; // Database name

$conn = new mysqli($serverName, $username, $password, $database);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/LandingPagee.css">
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Internship Monitoring System</title>
</head>
<body>

<nav>
        <ul style="list-style-type: none; padding: 0;">
            <li style="display: flex; align-items: center; margin-top: 10px;">
                <a href="LandingPage.php" class="logo-link" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="image/icon-72.png" class="logo-img" style="max-height: 51px; margin-right: 10px;" />
                    <span class="logo-text" style="font-family: 'Robotolightnew', sans-serif; line-height: 1.2; text-align: left; font-size: 20px;">
                        Internship<br>Monitoring System
                    </span>
                </a>
            </li>
            </li>
            <div class="login-button">
            <a href="StudentRegister.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Intern Registration</a>
         </div>
		 <div class="login-button1">
            <a href="CompanyRegister.php" style="text-decoration: none; font-family: 'Robotolightnew', sans-serif;  color: white; font-weight: bold;">Company Registration</a>
         </div>
        </ul>
    </nav>
<div class="overlay"></div> <!-- Dark overlay added -->


    <div class="container">
        <div class="title"><h1 style="font-family: 'Robotolightnew', sans-serif;  font-weight: Bold;">Seamless Internship Tracking</h1></div>
        <h2 style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">INTERNSHIP MONITORING</h2>
        <p class="subtitle"><h3 style="font-family: 'Robotolightnew', sans-serif;  font-weight: normal;">Monitor Intern Attendance, Track Hours, and Generate Reports All In One System</h3></p>
        <div class="buttons">
            <button class="btn btn-primary" onclick="window.location.href='StudentLogin.php'" >Student Log In</button>
            <button class="btn btn-secondary" onclick="window.location.href='CompanyLogin.php'">Company Log In</button>
        </div>
    </div>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
