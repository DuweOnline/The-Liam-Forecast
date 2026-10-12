<?php
require_once __DIR__ . "/admin.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_check();
    $id = (int) ($_POST["id"] ?? 0);
    try {
        $stmt = $conn->prepare("DELETE FROM dates WHERE dateID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        $error = "Error deleting record: " . $e->getMessage();
    }
} else {
    $id = (int) ($_GET["id"] ?? 0);
}

// $id is cast to int above, so it is safe to use directly
$gig = $conn
    ->query(
        "SELECT locations.name, locations.country, dates.date
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
			<form action="delete.php" method="post">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="id" value="<?php echo $id; ?>"/>
				<?php if ($error !== "") { ?>
				<p><?php echo e($error); ?></p>
				<?php } ?>
				<?php if ($gig) { ?>
				<p>Are you sure you want to delete this gig?</p>
				<p class='intro'><?php echo e(date_format(date_create($gig["date"]), "jS F, Y") . ", " . $gig["name"] . ", " . $gig["country"]); ?></p>
				<?php } else { ?>
				<p>Gig not found</p>
				<?php } ?>
				<table>
          <tr>
            <?php if ($gig) { ?>
            <td>
					<button type="submit" value="Yes" class="danger">YES</button>
            </td>
            <?php } ?>
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
