<?php
require_once __DIR__ . "/admin.php";
$date_err = $location_err = $message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    csrf_check();
    $dateID = (int) ($_POST["date"] ?? 0);
    if ($dateID <= 0) {
        $date_err = "Please pick a date.";
    }
    $locationID = (int) ($_POST["location"] ?? 0);
    if ($locationID <= 0) {
        $location_err = "Please pick a location.";
    }
    if ($date_err === "" && $location_err === "") {
        try {
            $stmt = $conn->prepare("UPDATE dates SET locationID = ? WHERE dateID = ?");
            $stmt->bind_param("ii", $locationID, $dateID);
            $stmt->execute();
            $message = $stmt->affected_rows > 0 ? "Date linked to location" : "That date could not be found";
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
$dates = fetch_rows($conn->query("SELECT dateID, date FROM dates WHERE locationID IS NULL ORDER BY date ASC"));
$locations = fetch_rows($conn->query("SELECT locationID, name, country FROM locations"));
$conn->close();
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
	<main class="oasis">
    <form action="link_date.php" method="post">
    <?php echo csrf_field(); ?>
    <div class="wrap">
		<table>
      <thead>
        <tr><th scope="col">Date</th><th scope="col">Location</th><th scope="col"><span class="visually-hidden">Action</span></th></tr>
      </thead>
    <tbody>
      <tr>
        <td><label for="date" class="visually-hidden">Date</label><div class="select"><select id="date" name="date" required<?php echo error_attrs($date_err, "date-error"); ?>>
        <option value="" selected="selected" disabled="disabled">Pick Date</option>
      <?php
      if (count($dates) > 0) {
          foreach ($dates as $row) {
              echo "<option value='" .
                  (int) $row["dateID"] .
                  "'>" .
                  e(date_format(date_create($row["date"]), "jS F, Y")) .
                  "</option>";
          }
      } else {
          echo "<option value='' disabled='disabled'>No Dates available</option>";
      }
      ?>
      </select><svg class="-mr-1 size-5 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false" data-slot="icon">
        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
      </svg></div><span class="invalid-feedback" id="date-error"><?php echo e($date_err); ?></span></td>
        <td><label for="location" class="visually-hidden">Location</label><div class="select"><select id="location" name="location" required<?php echo error_attrs($location_err, "location-error"); ?>>
      <option value="" selected="selected" disabled="disabled">Pick Location</option>
      <?php
      if (count($locations) > 0) {
          foreach ($locations as $row) {
              echo "<option value='" .
                  (int) $row["locationID"] .
                  "'>" .
                  e($row["name"] . ", " . $row["country"]) .
                  "</option>";
          }
      } else {
          echo "<option value='' disabled='disabled'>No Locations available</option>";
      }
      ?>
      </select><svg class="-mr-1 size-5 text-black" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false" data-slot="icon">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
          </svg></div><span class="invalid-feedback" id="location-error"><?php echo e($location_err); ?></span></td>
        <td><button type="submit" class="safe">LINK</button></td>
      </tr>
      <?php if ($message !== "") { ?>
      <tr><td colspan="3" role="alert"><?php echo e($message); ?></td></tr>
      <?php } ?>
      <tr>
              <td colspan="3"><a href="index.php"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
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
