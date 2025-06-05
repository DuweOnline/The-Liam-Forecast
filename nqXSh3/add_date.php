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
    if (empty($date_err)) {
        $sql = "INSERT INTO dates (date) VALUE ('" . $date . "')";

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
	<title>ADD DATE | The Liam Forecast</title>
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
        <tr><th>Add Date</th><th></th></tr>
      </thead>
    <tbody>
      <tr><td><input type="date" id="date" name="date"  class="<?php echo !empty(
          $date_err
      )
          ? "is-invalid"
          : ""; ?>" value="<?php echo $_REQUEST[
    "date"
]; ?>"><span class="invalid-feedback"><?php echo $date_err; ?></span></td><td><button type="submit" value="add" class="safe">Add</button></td></tr>
      <?php echo $success; ?>
      <tr>
        <td colspan="2"><a href="index.php"><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
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