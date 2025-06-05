<?php
session_start();
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
require_once "../config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_location = $_REQUEST["location"];
    if (empty($input_location)) {
        $location_err = "Please enter a location.";
    } else {
        $location = $input_location;
    }
    $input_country = $_REQUEST["country"];
    if (empty($input_country)) {
        $country_err = "Please enter a country.";
    } else {
        $country = $input_country;
    }
    $input_latitude = $_REQUEST["latitude"];
    if (empty($input_latitude)) {
        $latitude_err = "Please enter a latitude.";
    } else {
        $latitude = $input_latitude;
    }
    $input_longitude = $_REQUEST["longitude"];
    if (empty($input_longitude)) {
        $longitude_err = "Please enter a longitude.";
    } else {
        $longitude = $input_longitude;
    }
    if (
        empty($location_err) &&
        empty($country_err) &&
        empty($latitude_err) &&
        empty($longitude_err)
    ) {
        $sql =
            "INSERT INTO locations (name, country, latitude, longitude) VALUE ('" .
            $input_location .
            "','" .
            $input_country .
            "','" .
            $input_latitude .
            "','" .
            $input_longitude .
            "')";

        if ($conn->query($sql) === true) {
            $success = "<tr><td colspan='2'>New location added</td></tr>";
        } else {
            $success =
                "<tr><td colspan='2'>Error: " .
                $sql .
                "<br>" .
                $conn->error .
                "</td></tr>";
        }
    }
    $conn->close();
}
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
	<div class="oasis">
    <form action="<?php echo htmlspecialchars(
        $_SERVER["PHP_SELF"]
    ); ?>" method="post">
    <div class="wrap">
		<table>
      <thead>
        <tr><th>Location</th><th>Country</th><th>Latitude</th><th>Longitude</th><th></th></tr>
      </thead>
    <tbody>
      <tr><td><input type="text" id="location" name="location" class="<?php echo !empty(
          $location_err
      )
          ? "is-invalid"
          : ""; ?>" value="<?php echo $_REQUEST[
    "location"
]; ?>" placeholder="eg: Wembley Stadium"><span class="invalid-feedback"><?php echo $location_err; ?></span></td>
      <td><input type="text" id="country" name="country" class="<?php echo !empty(
          $country_err
      )
          ? "is-invalid"
          : ""; ?>" value="<?php echo $_REQUEST[
    "country"
]; ?>" placeholder="eg: England"><span class="invalid-feedback"><?php echo $country_err; ?></span></td>
      <td><input type="text" id="latitude" name="latitude" class="<?php echo !empty(
          $latitude_err
      )
          ? "is-invalid"
          : ""; ?>" value="<?php echo $_REQUEST[
    "latitude"
]; ?>" placeholder="eg: 51.556"><span class="invalid-feedback"><?php echo $latitude_err; ?></span></td><td><input type="text" id="longitude" name="longitude" class="<?php echo !empty(
    $longitude_err
)
    ? "is-invalid"
    : ""; ?>" value="<?php echo $_REQUEST[
    "longitude"
]; ?>" placeholder="eg: -0.282198"><span class="invalid-feedback"><?php echo $longitude_err; ?></span></td><td><button type="submit" value="add" class="safe">Add</button></td></tr>
      <?php echo $success; ?>
      <tr>
        <td colspan="5"><a href="index.php"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
          </svg>
 Back to list</a></td>
      </tr>
    </tbody>
    </table> 
    </div>
			</form>
	</div>
</body>
</html>