<?php
$query = false;
if(isset($_POST['city']) || isset($_GET['city'])){
	if(isset($_GET['city'])){$query = $_GET['city'];}
	if(isset($_POST['city'])){$query = $_POST['city'];}
	$request = 'http://api.openweathermap.org/data/2.5/weather?APPID=ed241873cccaffc909ed10107a5cc726&q='.urlencode($query);
	$jsonobj  = json_decode(file_get_contents($request), true);
	$wxData = array(
		'cod' =>		$jsonobj['cod'],
		'search' =>		ucwords($query),
		'temp' =>       empty($jsonobj['main']['temp']) ? '0' : $jsonobj['main']['temp'],
        'humidity' =>   empty($jsonobj['main']['humidity']) ? '0' : $jsonobj['main']['humidity'],
        'pressure' =>   empty($jsonobj['main']['pressure']) ? '0' : $jsonobj['main']['pressure'],
        'temp_max' =>   empty($jsonobj['main']['temp_max']) ? '0' : $jsonobj['main']['temp_max'],
        'temp_min' =>   empty($jsonobj['main']['temp_min']) ? '0' : $jsonobj['main']['temp_min'],
        'wind_deg' =>   empty($jsonobj['wind']['deg']) ? '0' :  $jsonobj['wind']['deg'],
        'wind_speed' => empty($jsonobj['wind']['speed']) ? '0' : $jsonobj['wind']['speed'],
        'name' =>       empty($jsonobj['name']) ? '0' : $jsonobj['name'],
        'conditions' => empty($jsonobj['weather']['0']['main']) ? '0' : $jsonobj['weather']['0']['main'],
        'icon' => 		empty($jsonobj['weather']['0']['icon']) ? '0' : $jsonobj['weather']['0']['icon']
    );
	require_once('format-weather.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>10k Weather</title>
	<link rel="stylesheet" href="css/style.min.css">
</head>
<body>
	<div class="container">
		<div class="content-wrap">
			<h1 class="title">Your Weather</h1>
			<div class="form-wrap">
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
					<div>
						<input type="text" name="city" id="location-input" value="<?php if($query == TRUE){echo $wxData['search'];}?>" pattern="^[A-Za-z, ]*$" required autofocus />
						<label for="location-input">City, State</label>
					</div>
					<input id="submit-form" type="submit" name="submit" class="location-submit" value="Search" />
				</form>
			</div>
			<div class="wx-container"><?php if($query == TRUE){echo $wxHtml;}?></div>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>