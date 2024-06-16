<?php
	session_start();
    if(!isset($_SESSION['logged_in']) OR $_SESSION['logged_in'] == 0)
	{
		$_SESSION['message'] = "You need to first login to access this page !!!";
		header("Location: Login/error.php");
	}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    
		<meta charset="UTF-8">
		<title>E-Agro</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="bootstrap\css\bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="bootstrap\js\bootstrap.min.js"></script>
		<!-- [if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif] -->
		<link rel="stylesheet" href="login.css"/>
		<link rel="stylesheet" type="text/css" href="indexFooter.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	<?php require 'menu.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        ::placeholder{
            font-family: Arial, sans-serif;
            text-align:center;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        h3{
            margin-left:600px
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        label {
            font-size: 18px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-left: 600px;
            width:50vh;
            
            
        }
        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 30vh;
            margin-left:20px;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .weather-info {
            text-align: center;
            margin-top: 20px;
        }
        .forecast {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .forecast-card {
            padding: 10px;
            margin: 10px;
            background-color: #fff;
            border-radius: 5px;
            /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
            /* box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px; */
            box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
            /* box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset, rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset, rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px; */
            width: 300px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h2>Weather App</h2>

    <form method="post" action="">
        <!-- <label for="city">Enter City:</label> -->
        <input type="text" id="city" name="city" placeholder="Enter The City" required>
        <button type="submit">Get Weather</button>
    </form>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['city'])) {
    $apiKey = "ec4bc65154eaba807c1aba7ec7fd3d9d"; // Replace with your API key
    $city = $_POST['city'];
    $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

    // Fetch current weather data
    $current_response = file_get_contents($url);
    $current_data = json_decode($current_response, true);

    if ($current_data['cod'] == 200) {
        $weather = $current_data['weather'][0]['description'];
        $temp = $current_data['main']['temp'];
        $humidity = $current_data['main']['humidity'];

        echo "<div class='weather-info'>";
        echo "<h3>Current Weather in $city:</h3>";
        echo "<p>Description: $weather</p>";
        echo "<p>Temperature: $temp °C</p>";
        echo "<p>Humidity: $humidity%</p>";
        echo "</div>";

        // Fetch 5-day forecast data
        $forecast_url = "http://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric";
        $forecast_response = file_get_contents($forecast_url);
        $forecast_data = json_decode($forecast_response, true);

        if ($forecast_data['cod'] == "200") {
            echo "<div class='forecast'>";
            $today = time();
            $two_days_later = strtotime('+1 days', $today);

            foreach ($forecast_data['list'] as $forecast) {
                $date = date('Y-m-d H:i:s', $forecast['dt']);
                // Check if the forecast date is within the next 2 days
                if (strtotime($date) <= $two_days_later) {
                    $weather = $forecast['weather'][0]['description'];
                    $temp = $forecast['main']['temp'];
                    $humidity = $forecast['main']['humidity'];
                    echo "<div class='forecast-card'>";
                    echo "<p>Date: $date</p>";
                    echo "<p>Description: $weather</p>";
                    echo "<p>Temperature: $temp °C</p>";
                    echo "<p>Humidity: $humidity%</p>";
                    echo "</div>";
                }
            }
            echo "</div>";
        } else {
            echo "<p class='weather-info'>Error fetching forecast data</p>";
        }
    } else {
        echo "<p class='weather-info'>Error: City not found</p>";
    }
}
?>



</body>
</html>