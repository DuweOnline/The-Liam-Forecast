<?php
session_start();
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>UPDATE | The Liam Forecast</title>
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
      basename($_SERVER["REQUEST_URI"])
  ); ?>" method="post">
  <div class="wrap">
		<table>
			<thead>
			<tr>
			  <th>Date</th>
			  <th>Location</th>
		  <th></th>
			</tr>
			</thead>
		  <tbody>
			  <tr>
				  <?php
      $sql =
          "SELECT dates.dateID, locations.name, locations.country, locations.latitude, locations.longitude, dates.date, dates.code, dates.temperature
									  FROM locations
									  INNER JOIN dates ON locations.locationID = dates.locationID
									  WHERE dates.dateID = " . $_GET["id"];
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $date = date_create($row["date"]);
              echo "<td><input type='date' value='" .
                  $row["date"] .
                  "' id='date' name='date'></td><td>" .
                  $row["name"] .
                  "</td>";
          }
      }
      mysqli_close($conn);
      ?>
	  <td>
		  <button type="submit" class="safe">UPDATE</button>
	  </td>
			  </tr>
			  <tr>
					  <td colspan="3"><a href="index.php"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
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