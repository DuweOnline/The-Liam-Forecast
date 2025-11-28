<?php
	require_once "config.php";
	$sql = "SELECT locations.name, locations.country, locations.latitude, locations.longitude, dates.date, dates.code, dates.temperature
FROM locations
INNER JOIN dates ON locations.locationID = dates.locationID
WHERE
	dates.date >= CURDATE()
AND
dates.date <= DATE_ADD(CURDATE(), INTERVAL 2 WEEK)";
//WHERE dates.date < DATE_ADD(CURDATE(), INTERVAL 2 WEEK) ORDER BY dates.date ASC";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$code = $row["code"];
			switch ($code) {
				case 0:
					$conditions = "Clear Sky";
					$bg = "sunshine";
					$lyric =
						"Sunsheeeine. It’s warm outside!";
					$sound = "audio/sunshine.mp3";
					break;
				case 1:
				case 2:
				case 3:
					$conditions =
						"Mainly Clear<br>Partly Cloudy";
					$bg = "cloudy";
					$lyric = "The weaaather is calm";
					$sound = "audio/calm.mp3";
					break;
				case 45:
				case 48:
					$conditions = "Fog";
					$bg = "fog";
					$lyric = "Fog!";
					$sound = "audio/fog.mp3";
					break;
				case 51:
				case 53:
				case 55:
					$conditions = "Light Drizzle";
					$bg = "rain";
					$lyric = "Raiiin!";
					$sound = "audio/rain.mp3";
					break;
				case 56:
				case 57:
					$conditions = "Freezing Drizzle";
					$bg = "rain";
					$lyric = "Cooold and wind and rain";
					$sound = "audio/coldwindrain.mp3";
					break;
				case 61:
				case 63:
				case 65:
				case 66:
				case 67:
					$conditions = "Rain";
					$bg = "rain";
					$lyric =
						"Raiiin! I don’t see no shine";
					$sound = "audio/rainnoshine.mp3";
					break;
				case 71:
				case 73:
				case 75:
				case 77:
					$conditions = "Snow Fall";
					$bg = "snow";
					$lyric = "Snowstooorm";
					$sound = "audio/snow.mp3";
					break;
				case 80:
				case 81:
				case 82:
				case 85:
				case 86:
					$conditions = "Rain Showers";
					$bg = "rain";
					$lyric = "Raiiin! It soaks you to the bone";
					$sound = "audio/rainasitsoaksyoutothebone.mp3";
					break;
				case 95:
				case 96:
				case 99:
					$conditions = "Thunderstorm";
					$bg = "thunder";
					$lyric = "A storm!";
					$sound = "audio/storm.mp3";
					break;
			}
			$date = date_create($row["date"]);
			echo "<div class='dropdown-item' 
				data-sound='" .
				$sound .
				"' 
				data-lyric='" .
				$lyric .
				"' data-bg='" .
				$bg .
				"' data-day='" .
				date_format($date, "l") .
				"' data-date='" .
				date_format($date, "jS F, Y") .
				"' data-location='" .
				$row["name"] .
				", " .
				$row["country"] .
				"' data-code='" .
				$conditions .
				"' data-temperature='" .
				$row["temperature"] .
				"'>" .
				$row["country"] .
				": " .
				$row["name"] .
				" - " .
				date_format($date, "jS F Y") . " ".DATE(now())."asdasdasd</div>";
		}
	} else {
		echo "<div class='dropdown-disabled'>No gigs are within the next 2 weeks - please try later</div>";
	}
	mysqli_close($conn);
	?>