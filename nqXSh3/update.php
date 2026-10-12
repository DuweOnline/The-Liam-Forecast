<?php
require_once __DIR__ . "/admin.php";
$id = (int) ($_GET["id"] ?? 0);
$date_err = "";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $date = trim($_POST["date"] ?? "");
    if (!valid_date($date)) {
        $date_err = "Please enter a valid date.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE dates SET date = ? WHERE dateID = ?");
            $stmt->bind_param("si", $date, $id);
            $stmt->execute();
            $stmt->close();
            $message = "Date updated";
        } catch (mysqli_sql_exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// $id is cast to int above, so it is safe to use directly
$gig = $conn
    ->query(
        "SELECT dates.dateID, locations.name, locations.country, dates.date
        FROM locations
        INNER JOIN dates ON locations.locationID = dates.locationID
        WHERE dates.dateID = " . $id
    )
    ->fetch_assoc();
$conn->close();
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
	<main class="oasis">
		<form action="update.php?id=<?php echo $id; ?>" method="post">
		<?php echo csrf_field(); ?>
  <div class="wrap">
		<table>
			<thead>
			<tr>
			  <th scope="col">Date</th>
			  <th scope="col">Location</th>
		  <th scope="col"><span class="visually-hidden">Action</span></th>
			</tr>
			</thead>
		  <tbody>
			  <?php if ($gig) { ?>
			  <tr>
				  <td><label for="date" class="visually-hidden">Gig date</label><input type="date" value="<?php echo e($gig["date"]); ?>" id="date" name="date" required<?php echo error_attrs($date_err, "date-error"); ?>><span class="invalid-feedback" id="date-error"><?php echo e($date_err); ?></span></td>
				  <td><?php echo e($gig["name"] . ", " . $gig["country"]); ?></td>
				  <td>
					  <button type="submit" class="safe">UPDATE</button>
				  </td>
			  </tr>
			  <?php } else { ?>
			  <tr><td colspan="3" role="alert">Gig not found</td></tr>
			  <?php } ?>
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
