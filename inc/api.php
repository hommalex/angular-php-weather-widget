<?php
	
	$keyApi = 'kz88dr8r8m3a9r5cukcrvjy7';
	
	// PLEASE NOTE THE CRON WILL OPERATE THAT SHEET TO CREATE A JSON TO PUT ON A WEBSITE
	
	$towns = array( // not less han 8 cos the api can crash on some destination and there is no backup
		"ORL" => 		"orlando", 
		"LAS" => 		"las vegas", 
		"NYC" =>		"new york", 
		"CUN" =>		"cancun",
		"CAN" =>		"toronto",
		"MEM" =>		"memphis",
		"SFO" =>		"san francisco",
		"CHI" =>		"chicago",
		"STL" =>		"seattle",
		"BOS" =>		"boston"
	);

	$timenow = time();

	// Weather available for this API attach to picture "http://www.worldweatheronline.com/feed/wwoConditionCodes.txt"
	$weatherCodes = array(
		"113" => "wi-day-sunny",//Clear/Sunny
		"116" => "wi-day-sunny-overcast",//Partly Cloudy
		"119" => "wi-cloudy",//Cloudy
		"122" => "wi-day-sunny-overcast",//Overcast
		"143" => "wi-day-fog",//Mist
		"176" => "wi-day-rain-mix",//Patchy rain
		"179" => "wi-day-snow",//Patchy snow
		"182" => "wi-day-snow",//Patchy sleet
		"185" => "wi-sprinkle",//Patchy freezing drizzle
		"200" => "wi-day-thunderstorm",//Thundery outbreak
		"227" => "wi-snow",//Blowing snow
		"230" => "wi-snow",//Blizzard
		"248" => "wi-day-fog",//Fog
		"260" => "wi-day-fog",//Fog
		"263" => "wi-sprinkle",//Patchy light drizzle
		"266" => "wi-sprinkle",//drizzle
		"281" => "wi-sprinkle",//drizzle
		"284" => "wi-sprinkle",//Cdrizzle
		"293" => "wi-rain-mix",//drizzle
		"296" => "wi-showers",//drizzle
		"299" => "wi-showers",//Rain at time
		"302" => "wi-showers",//Rain at time
		"305" => "wi-rain",//Rain at time
		"308" => "wi-rain",//Rain Heavy
		"311" => "wi-showers",//Rain Heavy
		"314" => "wi-rain",//Rain Heavy
		"317" => "wi-sprinkle",//partly Snow
		"320" => "wi-rain-mix",//partly Snow
		"323" => "wi-day-snow",//partly Snow
		"326" => "wi-day-snow",//partly Snow
		"329" => "wi-day-snow",//partly Snow
		"332" => "wi-day-snow",//partly Snow
		"335" => "wi-day-snow",//heavy Snow
		"338" => "wi-day-snow",//heavy Snow
		"350" => "wi-hail",//heavy Snow
		"353" => "wi-sprinkle",//rain shower
		"356" => "wi-sprinkle",//rain shower
		"359" => "wi-rain",//Torrential rain shower
		"362" => "wi-sprinkle",//asdsadsadas
		"365" => "wi-sprinkle",//sadasdasdsa
		"368" => "wi-rain-mix",//light snow
		"371" => "wi-snow",//snow
		"374" => "wi-rain-mix",//light Shower
		"377" => "wi-snow",//Freeze
		"386" => "wi-day-storm-showers",//Thunder and rain
		"389" => "wi-day-storm-showers",//Thunder and snow
		"395" => "wi-day-storm-showers"
	);

	$jsong = '{';
	foreach ($towns as $key => $town) {
		$url = "http://api.worldweatheronline.com/free/v1/weather.ashx?key=".$keyApi."&cc=yes&q=".$town."&format=json&cc=yes";
		$url = str_replace(" ","%20",$url);
		$json = file_get_contents($url);
		$data = json_decode($json, TRUE);
		// echo "<pre>";
		// print_r($data['data']['request']);
		// echo "</pre>";
		
		$humidity =   		$data['data']['current_condition'][0]['humidity'];
		$temp_C =   		$data['data']['current_condition'][0]['temp_C'];
		$temp_F =    		$data['data']['current_condition'][0]['temp_F'];
		$weatherCode =   	$data['data']['current_condition'][0]['weatherCode'];
		$weatherDesc =   	$data['data']['current_condition'][0]['weatherDesc'][0]['value'];
		$windspeedKmph =    $data['data']['current_condition'][0]['windspeedKmph'];
		$thisplace =   		$data['data']['request'][0]['query'];
		if (strlen($temp_C) == 1) { $temp_C = "0".$temp_C; }
		if (strlen($temp_F) == 1) { $temp_F = "0".$temp_F; }
		
		if (strlen($thisplace) > 0) {
			if ( strpos($thisplace,"-") > 0 ) { $thisplace = ucwords(str_replace("-", " ", $thisplace)); }
			$jsong .= '"'.$thisplace.'": {"accro": "'.$key.'", "humidity": "'.$humidity.'", "temp_C": "'.$temp_C.'", "temp_F": "'.$temp_F.'", "weatherDesc": "'.$weatherDesc.'", "windspeedKmph": "'.$windspeedKmph.'", "img": "'.$weatherCodes[$weatherCode].'"}, ';
		}
		sleep(2); // too many requests at the same time crash the requests.
	}
	$jsong = substr($jsong,0,-2);
	$jsong .= '}';
	
	file_put_contents("/var/www/a.touramerica.ie/touramerica/weather/inc/api.json",$jsong);
	
	echo "Yes";

?>