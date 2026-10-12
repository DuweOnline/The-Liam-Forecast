<?php
require_once __DIR__ . "/admin.php";
$location = $country = $latitude = $longitude = "";
$location_err = $country_err = $latitude_err = $longitude_err = "";
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    csrf_check();
    $location = trim($_POST["location"] ?? "");
    if ($location === "") {
        $location_err = "Please enter a location.";
    }
    $country = trim($_POST["country"] ?? "");
    if ($country === "") {
        $country_err = "Please enter a country.";
    }
    $latitude = trim($_POST["latitude"] ?? "");
    if (!is_numeric($latitude) || abs((float) $latitude) > 90) {
        $latitude_err = "Please enter a latitude between -90 and 90.";
    }
    $longitude = trim($_POST["longitude"] ?? "");
    if (!is_numeric($longitude) || abs((float) $longitude) > 180) {
        $longitude_err = "Please enter a longitude between -180 and 180.";
    }
    if ($location_err === "" && $country_err === "" && $latitude_err === "" && $longitude_err === "") {
        try {
            $stmt = $conn->prepare("INSERT INTO locations (name, country, latitude, longitude) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $location, $country, $latitude, $longitude);
            $stmt->execute();
            $stmt->close();
            $message = "New location added";
        } catch (mysqli_sql_exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>ADD LOCATION | The Liam Forecast</title>
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
	<meta name="apple-mobile-web-app-title" content="The Liam Forecast" />
	<link rel="manifest" href="/site.webmanifest" />
	<link rel="stylesheet" href="/css/oasis.css" />
  </head>
<body>
	<main class="oasis">
    <form action="add_location.php" method="post">
    <?php echo csrf_field(); ?>
    <div class="wrap">
		<table>
      <thead>
        <tr><th scope="col">Location</th><th scope="col">Country</th><th scope="col">Latitude</th><th scope="col">Longitude</th><th scope="col"><span class="visually-hidden">Action</span></th></tr>
      </thead>
    <tbody>
      <tr><td><label for="location" class="visually-hidden">Venue name</label><input type="text" id="location" name="location" required class="<?php echo $location_err !== "" ? "is-invalid" : ""; ?>" value="<?php echo e($location); ?>" placeholder="eg: Wembley Stadium"<?php echo error_attrs($location_err, "location-error"); ?>><span class="invalid-feedback" id="location-error"><?php echo e($location_err); ?></span></td>
      <td><label for="country" class="visually-hidden">Country</label><input type="text" id="country" name="country" required class="<?php echo $country_err !== "" ? "is-invalid" : ""; ?>" value="<?php echo e($country); ?>" placeholder="eg: England"<?php echo error_attrs($country_err, "country-error"); ?>><span class="invalid-feedback" id="country-error"><?php echo e($country_err); ?></span></td>
      <td><label for="latitude" class="visually-hidden">Latitude</label><input type="text" id="latitude" name="latitude" required inputmode="decimal" class="<?php echo $latitude_err !== "" ? "is-invalid" : ""; ?>" value="<?php echo e($latitude); ?>" placeholder="eg: 51.556"<?php echo error_attrs($latitude_err, "latitude-error"); ?>><span class="invalid-feedback" id="latitude-error"><?php echo e($latitude_err); ?></span></td>
      <td><label for="longitude" class="visually-hidden">Longitude</label><input type="text" id="longitude" name="longitude" required inputmode="decimal" class="<?php echo $longitude_err !== "" ? "is-invalid" : ""; ?>" value="<?php echo e($longitude); ?>" placeholder="eg: -0.282198"<?php echo error_attrs($longitude_err, "longitude-error"); ?>><span class="invalid-feedback" id="longitude-error"><?php echo e($longitude_err); ?></span></td>
      <td><button type="submit" class="safe">Add</button></td></tr>
      <?php if ($message !== "") { ?>
      <tr><td colspan="5" role="alert"><?php echo e($message); ?></td></tr>
      <?php } ?>
      <tr>
        <td colspan="5"><a href="index.php"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
          </svg>
 Back to list</a></td>
      </tr>
    </tbody>
    </table>
    </div>
			</form>
	</main>
</body>
</html>
