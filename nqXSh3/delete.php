<?php
session_start();
if (!isset($_SESSION["account_loggedin"])) {
    header("Location: index.php");
    exit();
}
require_once "../config.php";
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $sql = "DELETE FROM dates WHERE dateID=" . $_POST["id"];

    if ($conn->query($sql) === true) {
        header("location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en-GB">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>DELETE | The Liam Forecast</title>
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
		<div class="alert">
			<h2 class="mt-5 mb-3">Delete Gig</h2>
			<form action="<?php echo htmlspecialchars(
       $_SERVER["PHP_SELF"]
   ); ?>" method="post">
				<input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
				<p>Are you sure you want to delete this gig?</p>
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
            echo "<p class='intro'>" .
                date_format($date, "jS F, Y") .
                ", " .
                $row["name"] .
                ", " .
                $row["country"] .
                "</p>";
        }
    }
    mysqli_close($conn);
    ?>
				<table>
          <tr>
            <td>
					<button type="submit" value="Yes" class="danger">YES</button>
            </td>
            <td>
					<a href="index.php" class="safe">No</a>
            </td>
          </tr>
        </table>
			</form>
		</div>
	</div>
</body>
</html>