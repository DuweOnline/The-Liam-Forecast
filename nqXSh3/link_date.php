<?php
session_start();
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
require_once "../config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_date = $_REQUEST["date"];
    if (empty($input_date)) {
        $date_err = "Please enter a date.";
    } else {
        $date = $input_date;
    }
    $input_location = $_REQUEST["location"];
    if (empty($input_location)) {
        $location_err = "Please enter a location.";
    } else {
        $location = $input_location;
    }
    if (empty($date_err) && empty($location_err)) {
        $sql =
            "UPDATE dates SET locationID='" .
            $location .
            "' WHERE dateID='" .
            $date .
            "'";

        if ($conn->query($sql) === true) {
            $success = "<tr><td colspan='2'>New date added</td></tr>";
        } else {
            $success =
                "<tr><td colspan='2'>Error: " .
                $sql .
                "<br>" .
                $conn->error .
                "</td></tr>";
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>LINK DATE/LOCATION | The Liam Forecast</title>
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
        <tr><th>Date</th><th>Location</th><th></th></tr>
      </thead>
    <tbody>
      <tr>
        <td><div class="select"><select id="date" name="date">
        <option selected="selected" disabled="disabled">Pick Date</option>
      <?php
      $sql =
          "SELECT dateID, date, locationID FROM dates WHERE locationID IS NULL";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          // output data of each row
          while ($row = $result->fetch_assoc()) {
              $date = date_create($row["date"]);
              echo "<option value='" .
                  $row["dateID"] .
                  "'>" .
                  date_format($date, "jS F, Y") .
                  "</option>";
          }
      } else {
          echo "<option disabled='disabled'>No Dates available</option>";
      }
      ?>
      </select><svg class="-mr-1 size-5 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
      </svg></div><span class="invalid-feedback"><?php echo $date_err; ?></span></td>
        <td><div class="select"><select id="location" name="location">
      <option selected="selected" disabled="disabled">Pick Location</option>
      <?php
      $sql2 = "SELECT locationID, name, country FROM locations";
      $result = $conn->query($sql2);
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<option value='" .
                  $row["locationID"] .
                  "'>" .
                  $row["name"] .
                  ", " .
                  $row["country"] .
                  "</option>";
          }
      } else {
          echo "<option disabled='disabled'>No Locations available</option>";
      }
      $conn->close();
      ?>
      </select><svg class="-mr-1 size-5 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
          </svg></div><span class="invalid-feedback"><?php echo $location_err; ?></span></td>
        <td><button type="submit" class="safe">LINK</button></td>
      </tr>
      <?php echo $success; ?>
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